<?php

namespace App\Http\Controllers;

use App\Helpers\General\EarningHelper;
use App\Helpers\PayMob;
use App\Mail\OfflineOrderMail;
use App\Models\Bundle;
use App\Models\Auth\User;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tax;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;

class CartController extends Controller
{

    private $currency;

    public function __construct()
    {
        $this->currency = getCurrency(config('app.currency'));
    }

    public function index(Request $request)
    {
        $ids = Cart::session(auth()->user()->id)->getContent()->keys();
        $course_ids = [];
        $bundle_ids = [];
        $courseData = [];
        $total = 0;
        foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
            if ($item->attributes->type == 'bundle') {
                $bundle_ids[] = $item->id;
            } else {
                $course_ids[] = $item->id;
                if ($item->attributes->selectedDate && $item->attributes->selectedTime) {
                    $courseData[$item->id]['selectedDate'] = $item->attributes->selectedDate;
                    $courseData[$item->id]['selectedTime'] = $item->attributes->selectedTime;
                    $courseData[$item->id]['offlinePrice'] = $item->attributes->offlinePrice;
                }
            }
        }
        $courses = new Collection(Course::withoutGlobalScope('filter')->find($course_ids));
        $bundles = Bundle::find($bundle_ids);
        $courses = $bundles->merge($courses);

        $total = Cart::session(auth()->user()->id)->getContent()->sum('price');
        //Apply Tax
        $taxData = $this->applyTax('total');

        return view('frontend.cart.checkout', compact('courses', 'bundles', 'total', 'taxData', 'courseData'));
    }

    public function addToCart(Request $request)
    {

        $product = "";
        $teachers = "";
        $type = "";
        if ($request->has('course_id')) {
            $product = Course::withoutGlobalScope('filter')->findOrFail($request->get('course_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'course';
        } elseif ($request->has('bundle_id')) {
            $product = Bundle::findOrFail($request->get('bundle_id'));
            $teachers = $product->user->name;
            $type = 'bundle';
        }

        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();
        if (!in_array($product->id, $cart_items)) {
            Cart::session(auth()->user()->id)
                ->add(
                    $product->id,
                    $product->title,
                    $product->price,
                    1,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->description,
                        'image' => $product->course_image,
                        'type' => $type,
                        'teachers' => $teachers,
                    ]
                );
        }

        return redirect()->back()->with(['success' => trans('labels.frontend.cart.product_added')]);
    }

    public function checkout(Request $request)
    {
        $product = "";
        $teachers = "";
        $type = "";
        $bundle_ids = [];
        $course_ids = [];
        if ($request->has('course_id')) {
            $product = Course::withoutGlobalScope('filter')->findOrFail($request->get('course_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'course';
        } elseif ($request->has('bundle_id')) {
            $product = Bundle::findOrFail($request->get('bundle_id'));
            $teachers = $product->user->name;
            $type = 'bundle';
        }

        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();
        if (!in_array($product->id, $cart_items)) {

            Cart::session(auth()->user()->id)
                ->add(
                    $product->id,
                    $product->title,
                    $product->price,
                    1,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->description,
                        'image' => $product->course_image,
                        'type' => $type,
                        'teachers' => $teachers
                    ]
                );
        }
        foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
            if ($item->attributes->type == 'bundle') {
                $bundle_ids[] = $item->id;
            } else {
                $course_ids[] = $item->id;
            }
        }
        $courses = new Collection(Course::withoutGlobalScope('filter')->find($course_ids));
        $bundles = Bundle::find($bundle_ids);
        $courses = $bundles->merge($courses);

        $total = Cart::session(auth()->user()->id)->getContent()->sum('price');


        //Apply Tax
        $taxData = $this->applyTax('total');


        return view('frontend.cart.checkout', compact('courses', 'total', 'taxData'));
    }

    public function clear(Request $request)
    {
        Cart::session(auth()->user()->id)->clear();
        return back();
    }

    public function remove(Request $request)
    {
        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');


        if (Cart::session(auth()->user()->id)->getContent()->count() < 2) {
            Cart::session(auth()->user()->id)->clearCartConditions();
            Cart::session(auth()->user()->id)->removeConditionsByType('tax');
            Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
            Cart::session(auth()->user()->id)->clear();
        }
        Cart::session(auth()->user()->id)->remove($request->course);
        return redirect(route('cart.index'));
    }

    public function stripePayment(Request $request)
    {
        // dd($request);
        if ($this->checkDuplicate()) {
            return $this->checkDuplicate();
        }
        //Making Order
        $order = $this->makeOrder();
        //payment start
        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey(config('services.stripe.secret'));
        $token = $request->reservation['stripe_token'];

        $amount = Cart::session(auth()->user()->id)->getTotal();
        $currency = $this->currency['short_code'];
        $response = $gateway->purchase([
            'amount' => $amount,
            'currency' => $currency,
            'token' => $token,
            'confirm' => true,
            'description' => auth()->user()->name
        ])->send();
        //if payment successful
        if ($response->isSuccessful()) {
            $order->status = 1;
            $order->payment_type = 1;
            $order->save();

            (new EarningHelper)->insert($order);
            foreach ($order->items as $orderItem) {
                $course = $orderItem->item->course;
                if ($course->offline) {
                    $date = $course->date ? json_decode(json_decode($course->date), true) : null;
                    if ($date) {
                        if (in_array($orderItem->selectedDate, array_column($date, 'date'))) {
                            $selectDate = $date[array_search($orderItem->selectedDate, array_column($date, 'date'))];
                            foreach ($selectDate as $key => $value) {
                                if ($key != 'date') {
                                    if ($value == $orderItem->selectedTime) {
                                        $incrementKey = explode('-', $key);
                                        $seats = intval($selectDate['seats-' . $incrementKey[1]]) - 1;
                                        $date[array_search($orderItem->selectedDate, array_column($date, 'date'))]['seats-' . $incrementKey[1]] = $seats;
                                        $course->date = json_encode(json_encode($date));
                                    }
                                }
                            }
                        }
                    }
                }
                //Bundle Entries
                if ($orderItem->item_type == Bundle::class) {
                    foreach ($orderItem->item->courses as $course) {
                        $course->students()->attach($order->user_id);
                        if ($course->offline) {
                            $date = $course->date ? json_decode(json_decode($course->date), true) : null;
                            if ($date) {
                                if (in_array($orderItem->selectedDate, array_column($date, 'date'))) {
                                    $selectDate = $date[array_search($orderItem->selectedDate, array_column($date, 'date'))];
                                    foreach ($selectDate as $key => $value) {
                                        if ($key != 'date') {
                                            if ($value == $orderItem->selectedTime) {
                                                $incrementKey = explode('-', $key);
                                                $seats = intval($selectDate['seats-' . $incrementKey[1]]) - 1;
                                                $date[array_search($orderItem->selectedDate, array_column($date, 'date'))]['seats-' . $incrementKey[1]] = $seats;
                                                $course->date = json_encode(json_encode($date));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if (!$orderItem->item->offline) {
                    $orderItem->item->students()->attach($order->user_id);
                }
            }

            //Generating Invoice
            generateInvoice($order);

            Cart::session(auth()->user()->id)->clear();
            Session::flash('success', trans('labels.frontend.cart.payment_done'));
            return redirect()->route('status');
        } else {
            $order->status = 2;
            $order->save();
            \Log::info($response->getMessage() . ' for id = ' . auth()->user()->id);
            Session::flash('failure', trans('labels.frontend.cart.try_again'));
            return redirect()->route('cart.index');
        }
    }

    public function paypalPayment(Request $request)
    {


        if ($this->checkDuplicate()) {
            return $this->checkDuplicate();
        }

        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId(config('paypal.client_id'));
        $gateway->setSecret(config('paypal.secret'));
        $mode = config('paypal.settings.mode') == 'sandbox' ? true : false;
        $gateway->setTestMode($mode);

        $cartTotal = Cart::session(auth()->user()->id)->getTotal();
        // $currency = $this->currency['short_code'];

        try {
            $response = $gateway->purchase([
                'amount' => $cartTotal,
                'currency' => 'EGP',
                'description' => auth()->user()->name,
                'cancelUrl' => route('cart.paypal.status', ['status' => 0]),
                'returnUrl' => route('cart.paypal.status', ['status' => 1]),

            ])->send();
            if ($response->isRedirect()) {
                return Redirect::away($response->getRedirectUrl());
            }
        } catch (\Exception $e) {
            \Session::put('failure', trans('labels.frontend.cart.unknown_error'));
            return Redirect::route('cart.paypal.status');
        }

        \Session::put('failure', trans('labels.frontend.cart.unknown_error'));
        return Redirect::route('cart.paypal.status');
    }

    public function fawryPayment(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $number = str_random(8);
        $amount = Cart::session(auth()->user()->id)->getTotal();

        $merchantRefNum = str_random(8);
        // $description = 'Payment for Package subscription Request: '.$paymentAttempt->number;
        if (strpos($amount, '.') !== false) {
            $amount = round($amount, 2);
        } else {
            $amount = $amount . '.00';
        }
        $fawryUrl = 'https://atfawry.fawrystaging.com//ECommerceWeb/Fawry/payments/charge';

        // Generate Fawry Signature
        $merchantCode = config('fawry.merchant_code');
        $customerProfileId = auth()->user()->id;
        $paymentMethod = 'PAYATFAWRY';
        $secureKey = config('fawry.security_key');
        $buffer = $merchantCode . $merchantRefNum . $customerProfileId . $paymentMethod . $amount . $secureKey;
        $signature = hash('sha256', $buffer);
        $fawryData = [
            'merchantCode' => $merchantCode,
            'customerProfileId' => $customerProfileId,
            'customerMobile' => '01149786203',
            // 'customerEmail'=>$user->email,
            //'customerName'=>$user->name,
            'paymentMethod' => $paymentMethod,
            'amount' => $amount,
            //'paymentExpiry'=>'72',
            // 'chargeItems'=> $invoiceDataArray,
            'signature' => $signature,
            'merchantRefNum' => $merchantRefNum,
            // 'description'=> $description
        ];

        $data_string = json_encode($fawryData);

        $ch = curl_init($fawryUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));

        $coupon = Cart::session(auth()->user()->id)->getConditionsByType('coupon')->first();
        if ($coupon != null) {
            $coupon = Coupon::where('code', '=', $coupon->getName())->first();
        }
        $result = curl_exec($ch);
        $result = json_decode($result, true);
        $description = $result['statusDescription'];
        $order = new Order();
        $order->user_id = $customerProfileId;
        $order->reference_no = $merchantRefNum;
        $order->amount = $amount;
        $order->payment_type = 4;
        $order->status = 0;
        $order->coupon_id = ($coupon == null) ? 0 : $coupon->id;
        $order->fawry_status = $result['statusDescription'];
        if ($result['statusCode'] == 200) {
            $order->fawry_ref_no = $result['referenceNumber'];
            $order->fawry_expirationTime = $result['expirationTime'];
        }
        $order->save();
        foreach (Cart::session(auth()->user()->id)->getContent() as $cartItem) {
            if ($cartItem->attributes->type == 'bundle') {
                $type = Bundle::class;
            } else {
                $type = Course::class;
            }
            $order->items()->create([
                'item_id' => $cartItem->id,
                'selectedDate' => $cartItem->attributes->selectedDate ?? null,
                'selectedTime' => $cartItem->attributes->selectedTime ?? null,
                'item_type' => $type,
                'price' => $cartItem->price
            ]);
        }

        return redirect()->route('courses.all');
    }

    public function payMobPayment(Request $request)
    {
        $payMob = new PayMob();
        $amount = Cart::session(auth()->user()->id)->getTotal();
        $coupon = Cart::session(auth()->user()->id)->getConditionsByType('coupon')->first();

        if ($coupon != null) {
            $coupon = Coupon::where('code', '=', $coupon->getName())->first();
        }
        $coupon = ($coupon == null) ? 0 : $coupon->id;
        $items = [];
        $counter = 0;
        foreach (Cart::session(auth()->user()->id)->getContent() as $key => $cartItem) {
            $counter++;
            if ($cartItem->attributes->type == 'bundle') {
                $type = Bundle::class;
            } else {
                $type = Course::class;
            }
            $item = (object)[
                'item_id' => $cartItem->id,
                'type' => $type,
                'number' => $counter,
                'name' => $cartItem->name,
                'amount_cents' => $cartItem->price * 100,
                'selectedDate' => $cartItem->attributes->selectedDate ?? null,
                'selectedTime' => $cartItem->attributes->selectedTime ?? null,
                'quantity' => 1
            ];
            array_push($items, $item);
        }
        if (strpos($amount, '.') !== false) {
            $amount = round($amount, 2);
        } else {
            $amount = $amount . '.00';
        }
        $refNumber = str_random(8);
        // Step 1
        $payMob->authPaymob();
        // Step 2
        $paymobOrder = $payMob->makeOrderPaymob(
            $payMob->auth->profile->id, // merchant id.
            $amount * 100, // total amount by cents/piasters.
            $refNumber, // your (merchant) order id.
            $items
        );
        if (!$request->mobileNumber) {
            // Step 3
            $paymentKey = $payMob->getPaymentKeyPaymob(
                $amount * 100, // total amount by cents/piasters.
                $paymobOrder->shipping_data->order_id, // paymob order id from step 2.
                // For billing data
                auth()->user()->email, // optional
                auth()->user()->first_name, // optional
                auth()->user()->last_name, // optional
                auth()->user()->phone ? auth()->user()->phone : 'no-phone-found', // optional
                auth()->user()->city ? auth()->user()->city : 'NA' // optional
            );
            if ($this->checkDuplicate()) {
                return response()->json(['message' => 'You already have this course']);
            }
            //Making Order
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->reference_no = $refNumber;
            $order->amount = $amount;
            $order->payment_type = 5;
            $order->status = 0;
            $order->coupon_id = $coupon;
            $order->paymob_orderId = $paymobOrder->shipping_data->order_id;
            $order->save();
            foreach ($items as $item) {
                $order->items()->create([
                    'item_id' => $item->item_id,
                    'selectedDate' => $item->selectedDate ?? null,
                    'selectedTime' => $item->selectedTime ?? null,
                    'item_type' => $item->type,
                    'price' => $item->amount_cents / 100
                ]);
            }
            return response()->json(['paymentKey' => $paymentKey->token, 'url' => 'https://accept.paymob.com/api/acceptance/iframes/' . config('paymob.iframe_id') . '?payment_token=' . $paymentKey->token], 200);
        } else {
            $vodafonePayment = $payMob->vodafoneCashPayment(
                $request->mobileNumber,
                auth()->user()->first_name, // optional
                auth()->user()->last_name, // optional
                auth()->user()->email, // optional
                auth()->user()->phone ? auth()->user()->phone : 'no-phone-found' // optional
            );
            return response()->json(['payment' => $vodafonePayment]);
        }
    }

    public function processedCallback(Request $request)
    {
        $orderId = $request['obj']['order']['id'];
        // Statuses.
        $isSuccess = $request['obj']['success'];
        $isVoided = $request['obj']['is_voided'];
        $isRefunded = $request['obj']['is_refunded'];
        if ($isSuccess) {
            $order = Order::where('paymob_orderId', $orderId)->first();
            $order->transaction_id = $request['obj']['id'];
            $order->status = 1;
            $order->save();

            foreach ($order->items as $orderItem) {
                $course = $orderItem->item;
                if ($course->offline) {
                    $date = $course->date ? json_decode(json_decode($course->date), true) : null;
                    if ($date) {
                        if (in_array($orderItem->selectedDate, array_column($date, 'date'))) {
                            $selectDate = $date[array_search($orderItem->selectedDate, array_column($date, 'date'))];
                            foreach ($selectDate as $key => $value) {
                                if ($key != 'date') {
                                    if ($value == $orderItem->selectedTime) {
                                        $incrementKey = explode('-', $key);
                                        $seats = intval($selectDate['seats-' . $incrementKey[1]]) - 1;
                                        $date[array_search($orderItem->selectedDate, array_column($date, 'date'))]['seats-' . $incrementKey[1]] = $seats;
                                        $course->date = json_encode(json_encode($date));
                                    }
                                }
                            }
                        }
                    }
                }
                //Bundle Entries
                if ($orderItem->item_type == Bundle::class) {
                    foreach ($orderItem->item->courses as $course) {
                        $course->students()->attach($order->user_id);
                        if ($course->offline) {
                            $date = $course->date ? json_decode(json_decode($course->date), true) : null;
                            if ($date) {
                                if (in_array($orderItem->selectedDate, array_column($date, 'date'))) {
                                    $selectDate = $date[array_search($orderItem->selectedDate, array_column($date, 'date'))];
                                    foreach ($selectDate as $key => $value) {
                                        if ($key != 'date') {
                                            if ($value == $orderItem->selectedTime) {
                                                $incrementKey = explode('-', $key);
                                                $seats = intval($selectDate['seats-' . $incrementKey[1]]) - 1;
                                                $date[array_search($orderItem->selectedDate, array_column($date, 'date'))]['seats-' . $incrementKey[1]] = $seats;
                                                $course->date = json_encode(json_encode($date));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if (!$orderItem->item->offline) {
                    $orderItem->item->students()->attach($order->user_id);
                }
            }
        }
        Cart::session(auth()->user()->id)->clear();
        return response()->json(['success' => true], 200);
    }

    public function responseCallback(Request $request)
    {
        Cart::session(auth()->user()->id)->clear();
        return redirect()->route('status')->with(['success' => trans('labels.frontend.cart.purchase_successful')]);
    }

    public function offlinePayment(Request $request)
    {
        if ($this->checkDuplicate()) {
            return $this->checkDuplicate();
        }
        //Making Order
        $order = $this->makeOrder();
        $order->payment_type = 3;
        $order->status = 0;
        $order->save();
        $content = [];
        $items = [];
        $counter = 0;
        foreach (Cart::session(auth()->user()->id)->getContent() as $key => $cartItem) {
            $counter++;
            array_push($items, ['number' => $counter, 'name' => $cartItem->name, 'price' => $cartItem->price]);
        }

        $content['items'] = $items;
        $content['total'] = Cart::session(auth()->user()->id)->getTotal();
        $content['reference_no'] = $order->reference_no;

        try {
            \Mail::to(auth()->user()->email)->send(new OfflineOrderMail($content));
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' for order ' . $order->id);
        }
        $couponFromSession = Cart::session(auth()->user()->id)->getCondition('coupon');
        if ($couponFromSession) {
            $coupon_code = $couponFromSession->getAttributes()['code'];
            $coupon = Coupon::where('code', '=', $coupon_code)
                ->where('status', '=', 1)
                ->first();
            $coupon->status = 2;
            $coupon->save();
            $order->status = 1;
            $order->save();
            foreach ($order->items as $orderItem) {
                //Bundle Entries
                if ($orderItem->item_type == Bundle::class) {
                    foreach ($orderItem->item->courses as $course) {
                        $course->students()->attach($order->user_id);
                    }
                }
                $orderItem->item->students()->attach($order->user_id);
            }
        }

        //Generating Invoice
        generateInvoice($order);
        Cart::session(auth()->user()->id)->clear();
        \Session::flash('success', trans('labels.frontend.cart.offline_request'));
        return redirect()->route('admin.dashboard');
    }

    public function getPaymentStatus()
    {
        \Session::forget('failure');
        if (request()->get('status')) {
            if (empty(request()->get('PayerID')) || empty(request()->get('token'))) {
                \Session::put('failure', trans('labels.frontend.cart.payment_failed'));
                return Redirect::route('status');
            }
            $order = $this->makeOrder();
            $order->payment_type = 2;
            $order->transaction_id = request()->get('paymentId');
            $order->save();
            \Session::flash('success', trans('labels.frontend.cart.payment_done'));
            $order->status = 1;
            $order->save();
            (new EarningHelper)->insert($order);
            foreach ($order->items as $orderItem) {
                //Bundle Entries
                if ($orderItem->item_type == Bundle::class) {
                    foreach ($orderItem->item->courses as $course) {
                        $course->students()->attach($order->user_id);
                    }
                }
                if (!$orderItem->item->offline) {
                    $orderItem->item->students()->attach($order->user_id);
                }
            }

            //Generating Invoice
            generateInvoice($order);
            Cart::session(auth()->user()->id)->clear();
            return Redirect::route('status');
        } else {
            \Session::flash('failure', trans('labels.frontend.cart.payment_failed'));
            return Redirect::route('status');
        }
    }

    public function getNow(Request $request)
    {
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->reference_no = str_random(8);
        $order->amount = 0;
        $order->status = 1;
        $order->payment_type = 0;
        $order->save();
        //Getting and Adding items
        if ($request->course_id) {
            $type = Course::class;
            $id = $request->course_id;
            $boughtCourse = Course::withoutGlobalScope('filter')->findOrFail($id);
            $slug = $boughtCourse->slug;
        } else {
            $type = Bundle::class;
            $id = $request->bundle_id;
            $bundle = Bundle::findOrFail($id);
            $slug = $bundle->slug;
        }
        $order->items()->create([
            'item_id' => $id,
            'item_type' => $type,
            'price' => 0
        ]);

        foreach ($order->items as $orderItem) {
            //Bundle Entries
            if ($orderItem->item_type == Bundle::class) {
                foreach ($orderItem->item->courses as $course) {
                    $course->students()->attach($order->user_id);
                }
            }
            $orderItem->item->students()->attach($order->user_id);
        }
        Session::flash('success', trans('labels.frontend.cart.purchase_successful'));
        if ($type == Course::class) {
            return redirect()->route('courses.show', [$slug]);
        } else {
            return redirect()->route('bundles.show', [$slug]);
        }
    }

    public function getOffers()
    {
        $coupons = Coupon::where('status', '=', 1)->get();
        return view('frontend.cart.offers', compact('coupons'));
    }

    public function applyCoupon(Request $request)
    {
        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');

        $coupon = $request->coupon;
        $coupon = Coupon::where('code', '=', $coupon)
            ->where('status', '=', 1)
            ->first();
        if ($coupon != null) {
            Cart::session(auth()->user()->id)->clearCartConditions();
            Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
            Cart::session(auth()->user()->id)->removeConditionsByType('tax');

            $ids = Cart::session(auth()->user()->id)->getContent()->keys();
            $course_ids = [];
            $bundle_ids = [];
            foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
                if ($item->attributes->type == 'bundle') {
                    $bundle_ids[] = $item->id;
                } else {
                    $course_ids[] = $item->id;
                }
            }
            $courses = new Collection(Course::withoutGlobalScope('filter')->find($course_ids));
            $bundles = Bundle::find($bundle_ids);
            $courses = $bundles->merge($courses);

            $total = $courses->sum('price');
            $isCouponValid = false;
            if ($coupon->useByUser() < $coupon->per_user_limit) {
                $isCouponValid = true;
                if (($coupon->min_price != null) && ($coupon->min_price > 0)) {
                    if ($total >= $coupon->min_price) {
                        $isCouponValid = true;
                    }
                } else {
                    $isCouponValid = true;
                }
                if ($coupon->expires_at != null) {
                    if (Carbon::parse($coupon->expires_at) >= Carbon::now()) {
                        $isCouponValid = true;
                    } else {
                        $isCouponValid = false;
                    }
                }
            }

            if ($isCouponValid == true) {
                $type = null;
                if ($coupon->type == 1) {
                    $type = '-' . $coupon->amount . '%';
                } else {
                    $type = '-' . $coupon->amount;
                }

                $condition = new \Darryldecode\Cart\CartCondition(array(
                    'name' => 'coupon',
                    'type' => 'coupon',
                    'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                    'value' => $type,
                    'attributes' => [
                        'code' => $coupon->code,
                    ],
                    'order' => 1
                ));
                Cart::session(auth()->user()->id)->condition($condition);
                //Apply Tax
                $taxData = $this->applyTax('subtotal');

                $html = view('frontend.cart.partials.order-stats', compact('total', 'taxData'))->render();
                return ['status' => 'success', 'html' => $html];
            }
        }
        return ['status' => 'fail', 'message' => trans('labels.frontend.cart.invalid_coupon')];
    }

    public function removeCoupon(Request $request)
    {

        Cart::session(auth()->user()->id)->clearCartConditions();
        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
        Cart::session(auth()->user()->id)->removeConditionsByType('tax');

        $course_ids = [];
        $bundle_ids = [];
        foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
            if ($item->attributes->type == 'bundle') {
                $bundle_ids[] = $item->id;
            } else {
                $course_ids[] = $item->id;
            }
        }
        $courses = new Collection(Course::withoutGlobalScope('filter')->find($course_ids));
        $bundles = Bundle::find($bundle_ids);
        $courses = $bundles->merge($courses);

        $total = $courses->sum('price');

        //Apply Tax
        $taxData = $this->applyTax('subtotal');

        $html = view('frontend.cart.partials.order-stats', compact('total', 'taxData'))->render();
        return ['status' => 'success', 'html' => $html];
    }

    private function makeOrder()
    {
        $coupon = Cart::session(auth()->user()->id)->getConditionsByType('coupon')->first();
        if ($coupon != null) {
            $coupon = Coupon::where('code', '=', $coupon->getName())->first();
        }

        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->reference_no = str_random(8);
        $order->amount = Cart::session(auth()->user()->id)->getTotal();
        $order->status = 1;

        $order->coupon_id = ($coupon == null) ? 0 : $coupon->id;
        $order->payment_type = 3;
        $order->save();
        //Getting and Adding items
        foreach (Cart::session(auth()->user()->id)->getContent() as $cartItem) {
            if ($cartItem->attributes->type == 'bundle') {
                $type = Bundle::class;
            } else {
                $type = Course::class;
            }
            $order->items()->create([
                'item_id' => $cartItem->id,
                'selectedDate' => $cartItem->attributes->selectedDate ?? null,
                'selectedTime' => $cartItem->attributes->selectedTime ?? null,
                'item_type' => $type,
                'price' => $cartItem->price
            ]);
        }
        //        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
        return $order;
    }

    private function checkDuplicate()
    {
        $is_duplicate = false;
        $message = '';
        $orders = Order::where('user_id', '=', auth()->user()->id)->pluck('id');
        $order_items = OrderItem::whereIn('order_id', $orders)->get(['item_id', 'item_type']);
        foreach (Cart::session(auth()->user()->id)->getContent() as $cartItem) {
            if ($cartItem->attributes->type == 'course') {
                foreach ($order_items->where('item_type', 'App\Models\Course') as $item) {
                    if ($item->item_id == $cartItem->id) {
                        $is_duplicate = true;
                        $message .= $cartItem->name . ' ' . __('alerts.frontend.duplicate_course') . '</br>';
                    }
                }
            }
            if ($cartItem->attributes->type == 'bundle') {
                foreach ($order_items->where('item_type', 'App\Models\Bundle') as $item) {
                    if ($item->item_id == $cartItem->id) {
                        $is_duplicate = true;
                        $message .= $cartItem->name . '' . __('alerts.frontend.duplicate_bundle') . '</br>';
                    }
                }
            }
        }

        if ($is_duplicate) {
            return redirect()->back()->withdanger($message);
        }
        return false;
    }

    private function applyTax($target)
    {
        //Apply Conditions on Cart
        $taxes = Tax::where('status', '=', 1)->get();
        Cart::session(auth()->user()->id)->removeConditionsByType('tax');
        if ($taxes != null) {
            $taxData = [];
            foreach ($taxes as $tax) {
                $total = Cart::session(auth()->user()->id)->getTotal();
                $taxData[] = ['name' => '+' . $tax->rate . '% ' . $tax->name, 'amount' => $total * $tax->rate / 100];
            }

            $condition = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'Tax',
                'type' => 'tax',
                'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                'value' => $taxes->sum('rate') . '%',
                'order' => 2
            ));
            Cart::session(auth()->user()->id)->condition($condition);
            return $taxData;
        }
    }
}
