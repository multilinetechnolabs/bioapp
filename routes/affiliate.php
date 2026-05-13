<?php

// Route::get('/pricing', 'HomeController@pricing')->name('pricing');

Route::get('/products', 'ProductController@index')->name('products.index');
Route::get('/products/bio', 'ProductController@bio')->name('products.bio');
Route::get('/products/chakra', 'ProductController@chakra')->name('products.chakra');

Route::namespace('Affiliate')->group(function () {
    Route::get('/', 'HomeController@index')->name('root');
    Route::get('/', 'HomeController@index')->name('index');
    Route::post('/inquire', 'HomeController@inquire')->name('inquire');

    Route::get('/payment', 'PaymentController@index')->name('payment.index');
    Route::post('/payment', 'PaymentController@create')->name('payment');
    Route::get('/payment/status', 'PaymentController@status')->name('payment.status');
});
