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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware(['auth:api'])->group(function () {

    Route::group(['namespace' => 'Settings'], function () {
        /*
         * Category API routes
         */
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', 'CategoryController@index');
            Route::post('/', 'CategoryController@store');
            Route::patch('/{id}', 'CategoryController@update');
            Route::delete('/{id}', 'CategoryController@destroy');
        });

        /*
         * suppliers API routes
         */
        Route::group(['prefix' => 'suppliers'], function () {
            Route::get('/', 'SupplierController@index');
            Route::post('/', 'SupplierController@store');
            Route::patch('/{id}', 'SupplierController@update');
            Route::delete('/{id}', 'SupplierController@destroy');
            Route::get('/all', 'SupplierController@allSuppliers');
        });

        /*
         * products API routes
         */
        Route::group(['prefix' => 'products'], function () {
            Route::get('/', 'ProductController@index');
            Route::post('/', 'ProductController@store');
            Route::get('/{id}/show', 'ProductController@show');
            Route::patch('/{id}', 'ProductController@update');
            Route::delete('/{id}', 'ProductController@destroy');
            Route::get('/search/{value}', 'ProductController@search');
        });



        Route::group(['namespace' => 'Accounts'], function () {
            /*
             * Accounts API routes
             */

            Route::group(['prefix' => 'accounts'], function () {
                Route::get('/', 'AccountController@index');
                Route::post('/', 'AccountController@store');
                Route::patch('/{id}', 'AccountController@update');
                Route::delete('/{id}', 'AccountController@destroy');
                Route::get('/all', 'AccountController@allAccounts');
            });

            /*
             * payment / receipt routes
             */
            Route::group(['prefix' => 'transactions'], function () {
                Route::get('/', 'TransactionController@index');
                Route::post('/', 'TransactionController@store');
                Route::patch('/{id}', 'TransactionController@update');
                Route::delete('/{id}', 'TransactionController@destroy');
            });
        });
    });




    /*
     * purchases API routes
     */
    Route::group(['prefix' => 'purchase', 'namespace' => 'Purchase'], function () {
        Route::get('/', 'PurchaseController@index');
        Route::post('/', 'PurchaseController@store');
        Route::get('/{id}/edit', 'PurchaseController@edit');
        Route::patch('/{id}', 'PurchaseController@update');
        Route::delete('/{id}', 'PurchaseController@destroy');
        Route::get('/search/{value}', 'PurchaseController@search');
    });

    /*
     * customer API routes
     */
    Route::group(['prefix' => 'customer', 'namespace' => 'Customer'], function () {
        Route::get('/', 'CustomerController@index');
        Route::post('/', 'CustomerController@store');
        Route::patch('/{id}', 'CustomerController@update');
        Route::delete('/{id}', 'CustomerController@destroy');
        Route::get('/search/{value}', 'CustomerController@search');
        Route::get('/mobile/{value}/show', 'CustomerController@searchByMobile');
        Route::get('/all', 'CustomerController@allCustomers');
        Route::get('/due-customers', 'CustomerController@dueCustomers');
    });

    /*
     *  stock Routes
     */
    Route::group(['prefix' => 'reports', 'namespace' => 'Reports'], function () {
        Route::get('/stock', 'Stock@stock');
        Route::get('/instock', 'Stock@inStock');
        Route::get('/outofstock', 'Stock@outOfStock');
        Route::get('/{stocktype}/search/{value}', 'Stock@search');
    });


    /*
     * banks API routes
     */
    Route::group(['prefix' => 'banks', 'namespace' => 'Bank'], function () {
        Route::get('/', 'BankController@index');
        Route::post('/', 'BankController@store');
        Route::get('/{code}/show', 'BankController@show');
        Route::patch('/{id}', 'BankController@update');
        Route::delete('/{id}', 'BankController@destroy');


        //transactions api routes
        Route::get('/transactions', 'TransactionController@index');
        Route::post('/transaction', 'TransactionController@store');
        Route::patch('/transaction/{id}', 'TransactionController@update');
        Route::delete('/transaction/{id}', 'TransactionController@destroy');
    });

    /*
     * invoice API routes
     */
    Route::group(['prefix' => 'invoice', 'namespace' => 'Sells'], function () {
        Route::get('/', 'InvoiceController@index');
        Route::post('/', 'InvoiceController@store');
        Route::get('/{id}/edit', 'InvoiceController@edit');
        Route::patch('/{id}', 'InvoiceController@update');
        Route::delete('/{id}', 'InvoiceController@destroy');
        Route::get('/search/{value}', 'InvoiceController@search');
    });

});

/*
 * Public Routes api
 */

/*
 * login namespace
 */
Route::group(['namespace' => 'Login'], function () {
    /*
     * Login Routes
     */
    Route::post('/login', 'Login@postLogin')->name('api.postLogin');

    /*
     * Forgot Password Routes
     */
    Route::group(['prefix' => 'forgotpassword'], function () {
        Route::post('/', 'ForgetPasswordController@postForgotPassword')->name('api.postForgotpassword');
    });
});
