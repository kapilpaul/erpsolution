<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*
|--------------------------------------------------------------------------
| Visitors group middleware
|--------------------------------------------------------------------------
 */

Route::middleware(['visitors'])->group(function () {
    /*
     * login namespace
     */
    Route::group(['namespace' => 'Login'], function () {
        /*
         * Login Routes
         */
        Route::get('/', 'Login@login')->name('login');
        Route::post('/login', 'Login@postLogin')->name('postLogin');

        /*
         * Activation by email Routes
         */
        Route::get('/activation/{email}/{activationcode}', 'ActivationController@activateUser');

        /*
         * Forgot Password Routes
         */
        Route::group(['prefix' => 'forgotpassword'], function () {
            Route::get('/', 'ForgetPasswordController@forgotPasword')->name('forgotpassword');
            Route::post('/', 'ForgetPasswordController@postForgotPassword')->name('postForgotpassword');
        });


        /*
         * Reset Routes
         */
        Route::group(['prefix' => 'reset'], function () {
            Route::get('/{email}/{code}', 'ForgetPasswordController@resetPassword')->name('resetPassword');
            Route::post('/{email}/{code}', 'ForgetPasswordController@postResetPassword')->name('postResetPassword');
        });
    });
});


/*
 * authentication checked group middleware
 */
Route::middleware(['authcheck'])->group(function () {
    Route::get('/dashboard', 'Settings\\DashboardController@index')->name('dashboard');

    Route::get('/logout', 'Login\\Login@logout')->name('logout');

    Route::group(['namespace' => 'Settings'], function () {
        /*
         * Category Routes
         */
//        Route::group(['prefix' => 'category'], function () {
//            Route::get('/', 'CategoryController@index')->name('category.index');
//            Route::get('/create', 'CategoryController@create')->name('category.create');
//        });

        /*
         * supplier Routes
         */
//        Route::group(['prefix' => 'suppliers'], function () {
//            Route::get('/', 'SupplierController@index')->name('suppliers.index');
//            Route::get('/create', 'SupplierController@create')->name('suppliers.create');
//            Route::get('/{id}/details', 'SupplierController@show')->name('suppliers.show');
//        });

        /*
         *  products Routes
         */
        Route::group(['prefix' => 'products'], function () {
            Route::get('/', 'ProductController@index')->name('products.index');
            Route::get('/create', 'ProductController@create')->name('products.create');
            Route::get('/{id}/details', 'ProductController@details')->name('products.details');
            Route::get('/import', 'ProductController@import')->name('products.import');
        });
    });


    /*
     *  products Routes
     */
    Route::group(['prefix' => 'purchase', 'namespace' => 'Purchase'], function () {
        Route::get('/', 'PurchaseController@index')->name('purchase.index');
        Route::get('/create', 'PurchaseController@create')->name('purchase.create');
        Route::get('/{id}/show', 'PurchaseController@show')->name('purchase.show');
        Route::get('/{id}/edit', 'PurchaseController@edit')->name('purchase.edit');
    });

    /*
     *  customer Routes
     */
    Route::group(['prefix' => 'customer', 'namespace' => 'Customer'], function () {
        Route::get('/', 'CustomerController@index')->name('customer.index');
        Route::get('/create', 'CustomerController@create')->name('customer.create');
        Route::get('/{id}/details', 'CustomerController@show')->name('customer.show');
    });

    /*
     *  stock Routes
     */
    Route::group(['prefix' => 'reports', 'namespace' => 'Reports'], function () {
        Route::get('/stock', 'Stock@stock')->name('reports.stock');
        Route::get('/instock', 'Stock@inStock')->name('reports.instock');
        Route::get('/outofstock', 'Stock@outOfStock')->name('reports.outofstock');
    });

    /*
     *  Bank Routes
     */
    Route::group(['prefix' => 'banks', 'namespace' => 'Bank'], function () {
        Route::get('/', 'BankController@index')->name('banks.index');
        Route::get('/create', 'BankController@create')->name('banks.create');
        Route::get('/{code}/show', 'BankController@show')->name('banks.show');
        Route::get('/transactions', 'TransactionController@index')->name('banks.transaction.index');
        Route::get('/transaction/create', 'TransactionController@create')->name('banks.transaction.create');
    });

    /*
     *  Invoice Routes
     */
    Route::group(['prefix' => 'invoice', 'namespace' => 'Sells'], function () {
        Route::get('/', 'InvoiceController@index')->name('invoice.index');
        Route::get('/create', 'InvoiceController@create')->name('invoice.create');
        Route::get('/{id}/show', 'InvoiceController@show')->name('invoice.show');
        Route::get('/{id}/edit', 'InvoiceController@edit')->name('invoice.edit');
    });

    /*
     * Accounts Routes
     */
    Route::group(['namespace' => 'Settings\\Accounts'], function () {
        Route::group(['prefix' => 'accounts'], function () {
            Route::get('/', 'AccountController@index')->name('accounts.index');
            Route::get('/create', 'AccountController@create')->name('accounts.create');
            Route::get('/{code}/show', 'AccountController@show')->name('accounts.show');
        });

        /*
         * payment / receipt routes
         */
        Route::group(['prefix' => 'transactions'], function () {
            Route::get('/', 'TransactionController@index')->name('transaction.index');
            Route::get('/{type}/create', 'TransactionController@create')->name('transaction.create');
            Route::any('/daily-summary', 'TransactionController@dailySummary')->name('transaction.dailySummary');
            Route::any('/daily-summary/pdf', 'TransactionController@summaryPdf')->name('transaction.summaryPdf');
        });
    });
});



//Route::get('/groupby' , function() {
//    $orders = DB::table('purchases')
//        ->select('supplier_id', 'purchase_date', DB::raw('SUM(total_amount) as total_amount'))
//        ->groupBy('supplier_id')
//        ->groupBy('purchase_date')
//        ->whereNotNull('purchase_date')
//        ->get();
//
//    return $orders;
//
//    $date = Carbon\Carbon::parse("2018-09-12")->format("Y-m-d");
//    $lastTransaction = collect(\App\Models\Settings\Accounts\Transaction::whereCategory('customer')
//                                                    ->whereReceiverId(103)
//                                                    ->whereDate('date', '>=', $date)
//                                                    ->whereNotNull('balance')
//                                                    ->orderBy('date', 'asc')
//                                                    ->get());
//
//
//
//    return $lastTransaction->chunk(4);
//});



