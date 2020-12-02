<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::group([],function (){
    Route::post('sociallogin/{provider}', 'Frontend\Auth\SocialAccountController@SocialSignup');
    Route::get('auth/{provider}/callback', 'Frontend\Auth\SocialAccountController@index')->where('provider', '.*');
    Route::get('get-user','ApiController@getMyAccount');

   
});

Route::group(['prefix' => 'v1','namespace'=>'v1'],function (){
    Route::get('sponsors','ApiController@getSponsors');
    Route::post('send-msg','ApiController@vectorylink');
    Route::get('get-countries', 'ApiController@getCountries');
    Route::get('edu-system/{country}', 'ApiController@getEduSystems');
    Route::get('edu-stage/{eduSystem}', 'ApiController@getEduStages');
    Route::post('single-course','ApiController@getSingleCourse');
    Route::post('teacher-courses','ApiController@getTeacherCourses');
    Route::post('teacher-bundles','ApiController@getTeacherBundles');
    Route::post('single-teacher','ApiController@getSingleTeacher');
    Route::post('all-courses','ApiController@coursesOfStatge');
    Route::get('allcourses/{statge_id}','ApiController@StatgeCourses');
    // Route::post('my-purchases','ApiController@getMyPurchases');
    // Route::post('teachers','ApiController@getTeachers');
    Route::post('CategoryCourse','ApiController@getCategoryCourses');
    Route::post('get-page','ApiController@getPage');

    Route::post('login','ApiController@login');
    Route::post('social-login','ApiController@SocialLogin');

    Route::post('sign-up','ApiController@signup');
    Route::get('login/en', 'ApiController@index')->name('login.index');
    Route::get('signup', 'ApiController@registerIndex')->name('register.index');
    Route::get('login/ar', 'ApiController@indexrtl')->name('loginrtl.indexrtl');
    
    Route::group([
        'prefix' => 'auth'
    ], function () {

        // Route::get('signup', 'ApiController@registerIndex')->name('register.index');

        Route::post('signup-save', 'ApiController@signup');

        Route::group([
            'middleware' => 'auth:api'
        ], function() {
            Route::post('logout', 'ApiController@logout');

        });
    });

    Route::group(['middleware' => 'auth:api'],function (){

        // Route::post('continue-login' , '')

        Route::get('get-user','ApiController@getMyAccount');

        Route::post('update-password','ApiController@updatePassword');
        Route::post('invoices','ApiController@getInvoices');
        Route::post('show-invoice','ApiController@showInvoice');

    Route::post('single-lesson','ApiController@getLesson');
    Route::post('single-test','ApiController@getTest');

    Route::post('add-to-cart','ApiController@addToCart');
    Route::post('add-to-wishlist','ApiController@addToWishlist');
    Route::get('get-myWishlist','ApiController@getMyWishlist');
    Route::post('remove-from-Wishlist','ApiController@removeFromWishlist');
    Route::post('update-account','ApiController@updateMyAccount');

        Route::post('create-country', 'ApiController@saveCountry');
        Route::get('country/{id}', 'ApiController@getCountry');
        Route::post('edit/country/{id}', 'ApiController@updateCountry');
        Route::delete('remove/country/{id}', 'ApiController@deleteCountry');
        Route::post('create/edu-system', 'ApiController@saveEduSystem');
        Route::get('edu-system/show/{id}', 'ApiController@getEduSystem');
        Route::post('edu-system/edit/{id}', 'ApiController@updateEduSystem');
        Route::delete('edu-system/remove/{id}', 'ApiController@deleteEduSystem');
        Route::post('create/edu-stage', 'ApiController@saveEduStage');
        Route::get('edu-stage/show/{id}', 'ApiController@getEduStage');
        Route::post('edu-stage/edit/{id}', 'ApiController@updateEduStage');
        Route::post('edu-stage/semesters', 'ApiController@assignSemesters');
        Route::post('edu-stage/semesters/remove', 'ApiController@removeSemestersFromStage');
        Route::delete('edu-stage/remove/{id}', 'ApiController@deleteEduStage');
        Route::get('get-semesters', 'ApiController@getSemesters');
        Route::post('create/semester', 'ApiController@saveSemester');
        Route::get('semester/{id}', 'ApiController@getSemester');
        Route::post('semester/edit/{id}', 'ApiController@updateSemester');
        Route::delete('semester/remove/{id}', 'ApiController@deleteSemester');
        Route::get('get-years', 'ApiController@getYears');
        Route::post('create/year', 'ApiController@saveYear');
        Route::get('year/{id}', 'ApiController@getYear');
        Route::post('year/edit/{id}', 'ApiController@updateYear');
        Route::delete('year/remove/{id}', 'ApiController@deleteYear');
        Route::post('courses','ApiController@getCourses');
        Route::post('bundles','ApiController@getBundles');
        Route::post('search','ApiController@search');
        Route::post('latest-news','ApiController@getLatestNews');
        Route::post('testimonials','ApiController@getTestimonials');
       
        Route::post('get-faqs','ApiController@getFaqs');
        Route::post('fawry-payment' , 'ApiController@fawryPayment') ; 
        Route::post('why-us','ApiController@getWhyUs');
        Route::post('contact-us','ApiController@saveContactUs');
        Route::post('submit-review','ApiController@submitReview');
        Route::post('update-review','ApiController@updateReview');
        Route::post('save-note','ApiController@saveNote');
        Route::post('add-note','ApiController@AddNewNote');
        Route::post('remove-note','ApiController@removeNote');
       
        Route::post('save-test','ApiController@saveTest');
        Route::post('video-progress','ApiController@videoProgress');
        Route::post('course-progress','ApiController@courseProgress');
        Route::post('generate-certificate','ApiController@generateCertificate');
        Route::post('single-bundle','ApiController@getSingleBundle');
        Route::post('getnow','ApiController@getNow');
        Route::post('remove-from-cart','ApiController@removeFromCart');
        Route::post('get-cart-data','ApiController@getCartData');
        Route::post('clear-cart','ApiController@clearCart');
        Route::post('payment-status','ApiController@paymentStatus');
        Route::post('get-blog','ApiController@getBlog');
        Route::post('blog-by-category','ApiController@getBlogByCategory');
        Route::post('blog-by-tag','ApiController@getBlogByTag');
        Route::post('add-blog-comment','ApiController@addBlogComment');
        Route::post('delete-blog-comment','ApiController@deleteBlogComment');
        Route::post('forum','ApiController@getForum');
        Route::post('create-discussion','ApiController@createDiscussion');
        Route::post('store-response','ApiController@storeResponse');
        Route::post('update-response','ApiController@updateResponse');
        Route::post('delete-response','ApiController@deleteResponse');
        Route::post('messages','ApiController@getMessages');
        Route::post('compose-message','ApiController@composeMessage');
        Route::post('reply-message','ApiController@replyMessage');
        Route::post('unread-messages','ApiController@getUnreadMessages');
        Route::post('search-messages','ApiController@searchMessages');
        Route::post('my-certificates','ApiController@getMyCertificates');
        Route::post('my-purchases','ApiController@getMyPurchases');
        Route::post('my-account','ApiController@getMyAccount');
        Route::post('update-account','ApiController@updateMyAccount');
        Route::post('subscribe-newsletter','ApiController@subscribeNewsletter');
        Route::post('offers','ApiController@getOffers');
        Route::post('apply-coupon','ApiController@applyCoupon');
        Route::post('remove-coupon','ApiController@removeCoupon');
        Route::post('order-confirmation','ApiController@orderConfirmation');
        Route::post('single-user','ApiController@getSingleUser');

    });
    Route::post('send-reset-link','ApiController');
    Route::post('configs','ApiController@getConfig');
});
