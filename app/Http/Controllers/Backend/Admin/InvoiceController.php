<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Course;
use App\Models\Order;
use Hashids\Hashids;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Storage;
class InvoiceController extends Controller
{
    /**
     * Get invoice list of current user
     *
     * @param Request $request
     */
    public function getIndex(){

        $invoices = auth()->user()->invoices()->whereHas('order')->get();

        return view('backend.invoices.index',compact('invoices'));
    }


    /**
     * Download order invoice
     *
     * @param Request $request
     */
    public function getInvoice(Request $request)
    {


        if (auth()->check()) {
            $hashid = new Hashids('',5);
            $order_id = $hashid->decode($request->order);
            $order_id = array_first($order_id);

            $order = Order::findOrFail($order_id);
            if (auth()->user()->isAdmin() || ($order->user_id == auth()->user()->id)) {
                if (Storage::exists('invoices/' . $order->invoice->url)) {
                    return Storage::download('invoices/' . $order->invoice->url);
                }
                return abort(404);
            }
        }
        return abort(404);
    }

    public function showInvoice(Request $request){


        if (auth()->check()) {
            $hashid = new Hashids('',5);
            $order_id = $hashid->decode($request->code);
            $order_id = array_first($order_id);

            $order = Order::findOrFail($order_id);
            if (auth()->user()->isAdmin() || ($order->user_id == auth()->user()->id)) {
                if (Storage::exists('invoices/' . $order->invoice->url)) {
                    return response()->file(Storage::path('invoices/' . $order->invoice->url));
                }
                return abort(404);
            }
        }
        return abort(404);
    }

}
