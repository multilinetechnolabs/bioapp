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

Auth::routes(['verify' => true]);

Route::get('/phpinfo', function () {
    phpinfo();
});

// Route::get('/signup', 'HomeController@signup')->name('app.signup');
// Route::get('/', 'DashboardController@index')->name('app.root');
// Route::get('/landing_page', 'HomeController@landing_page')->name('app.landing_page');
// Route::get('/introduction', 'IntroductionController@index');

Route::get('/', 'DashboardController@landing')->name('app.root');
Route::get('/home', 'DashboardController@landing')->name('app.home');
Route::get('/bodyscan/info', 'BodyScanController@info')->name('app.bodyscan.info');
Route::get('/bioconnect/info', 'BioConnectController@info')->name('app.bioconnect.info');
Route::get('/chakrascan/info', 'ChakraScanController@info')->name('app.chakrascan.info');
Route::get('/data_cache/info', 'DataCacheController@info')->name('app.data_cache.info');

Route::get('/free-protocol-pairs', 'DrGoizPairController@index')->name('app.dr_goiz_pairs');

Route::get('/products', 'ProductController@index')->name('app.products.index');
Route::get('/products/bio', 'ProductController@bio')->name('app.products.bio');
Route::get('/products/chakra', 'ProductController@chakra')->name('app.products.chakra');
Route::post('/products/checkout', 'ProductController@checkout')->name('app.products.checkout')->middleware('verified');
Route::post('/products/checkout_with_shipping', 'ProductController@checkoutWithShipping')->name('app.products.checkoutWithShipping')->middleware('verified');
Route::get('/products/shipping_address', 'ProductController@shippingAddress')->name('app.products.shippingAddress')->middleware('verified');
Route::post('/usps', 'UspsController@index')->name('app.products.usps')->middleware('verified');
;

Route::get('/orders/{id}/payment', 'OrderController@payment')->name('app.orders.payment')->middleware('verified');
Route::get('/orders/{id}/payment/status', 'OrderController@payment_status')->name('app.orders.payment.status')->middleware('verified');

Route::get('/magnetictherapyblog', 'BlogController@index')->name('app.blogs.index');
Route::get('/magnetictherapyblog/{slug}', 'BlogController@show')->name('app.blogs.show');

Route::post('/contact', 'ContactController@store')->name('contact.store');




// Pricing is public — accessible to guests and authenticated users alike
Route::get('/pricing', 'HomeController@pricing')->name('app.pricing');

Route::middleware('verified')->group(function () {

    // Dashboard + account management — no subscription check (user may need to pay)
    Route::get('/dashboard', 'DashboardController@index')->name('app.dashboard');
    Route::get('/orders', 'HomeController@orders')->name('app.user.orders');
    Route::get('/payments', 'HomeController@payments')->name('app.user.payments');
    Route::get('/subscriptions', 'HomeController@subscriptions')->name('app.user.subscriptions');

    ## Plans — no subscription check (these ARE the payment routes)
    Route::post('/plans/{id}/subscribe', 'PlanController@subscribe')->name('app.plans.subscribe');
    Route::get('/plans/{id}/subscribe/status', 'PlanController@status')->name('app.plans.subscribe.status');

    // All feature routes require an active subscription
    Route::middleware('subscriber')->group(function () {

        Route::get('/bioconnect', 'BioConnectController@index')->name('app.bioconnect');
        Route::get('/bioconnect/groups', 'BioConnectController@groups');

        # Body Scan
        Route::get('/bodyscan', 'BodyScanController@index')->name('app.bodyscan');
        # Chakra Scan
        Route::get('/chakrascan', 'ChakraScanController@index')->name('app.chakrascan');

        # Bio Connect
        Route::get('/bioconnect/profile', 'BioConnectController@profile');
        Route::get('/bioconnect/profile/update', 'BioConnectController@updateProfile');
        Route::get('/bioconnect/activities', 'BioConnectController@activities');
        Route::get('/bioconnect/friends', 'BioConnectController@friends');
        Route::get('/bioconnect/friends/find', 'BioConnectController@findFriend');
        Route::get('/bioconnect/friends/request', 'BioConnectController@request');
        Route::get('findfrienddata', 'BioConnectController@findFrienddata');
        Route::get('/bioconnect/groups/mydiscussion', 'BioConnectController@groups_mydiscussion');
        Route::get('/bioconnect/groups/mycomment', 'BioConnectController@groups_mycomment');
        Route::get('/bioconnect/groups/mostcomments', 'BioConnectController@groups_mostcomments');

        # Data Cache
        Route::get('/data_cache', 'DataCacheController@index')->name('app.data_cache');
        Route::get('/data_cache/client_info', 'DataCacheController@client_info');
        Route::get('/data_cache/clients', 'DataCacheController@client_info');
        Route::get('/data_cache/clients/{id}', 'DataCacheController@client_show');
        Route::get('/data_cache/clients/{id}/add_pairs', 'DataCacheController@add_pairs');
        Route::get('/data_cache/clients/{id}/bio', 'DataCacheController@client_bio');
        Route::get('/data_cache/clients/{id}/chakra', 'DataCacheController@client_chakra');
        Route::get('/data_cache/bio', 'DataCacheController@bio');
        Route::get('/data_cache/chakra', 'DataCacheController@chakra');
        Route::get('/data_cache/preferences', 'DataCacheController@preferences');
        Route::post('/data_cache/upload_logo', 'DataCacheController@uploadLogo');
        Route::post('/data_cache/upload_consent_form', 'DataCacheController@uploadConsentForm');
        Route::post('/data_cache/preferences/update', array('as' => 'updatePreferences', 'uses' => 'DataCacheController@updatePreferences'));
        Route::get('/data_cache/help', 'DataCacheController@help');

        ## Media - Audio
        Route::get('/media', 'MediaController@index');
        Route::post('/media', 'MediaController@store');
        Route::get('/media/datatables', 'MediaController@datatables');
        Route::post('/media/update/{id}', 'MediaController@update');
        Route::get('/media/delete/{id}', 'MediaController@destroy');
        Route::get('/media/all', 'MediaController@allmedia');
        Route::get('/media/{id}', 'MediaController@show');

        ## Playlist
        Route::get('/playlist', 'PlaylistController@index');
        Route::get('/playlist/datatables', 'PlaylistController@datatables');
        Route::get('/playlist/{id}', 'PlaylistController@show');
        Route::post('/playlist', 'PlaylistController@store');
        Route::post('/playlist/update/{id}', 'PlaylistController@update');
        Route::get('/playlist/delete/{id}', 'PlaylistController@destroy');
        Route::get('/playlist/media/{id}', 'PlaylistController@getMedia');
        Route::get('/playlist/allmedia/{id}', 'PlaylistController@allMedia');

        # Posts
        Route::get('/posts', 'PostController@index')->name('app.posts.index');
        Route::get('/posts/datatables', 'PostController@datatables');
        Route::get('/posts/new', 'PostController@new')->name('app.posts.new');
        Route::get('/posts/{id}', 'PostController@show')->name('app.posts.show');
        Route::get('/posts/{id}/edit', 'PostController@edit')->name('app.posts.edit');
        Route::post('/posts', 'PostController@store')->name('app.posts.store');
        Route::put('/posts/{id}', 'PostController@update')->name('app.posts.update');
        Route::delete('/posts/{id}', 'PostController@destroy')->name('app.posts.destroy');

        ## Scan Sessions
        Route::get('/scan_sessions/{id}/export', 'ScanSessionController@export');
        Route::get('/scan_sessions/{id}/print', 'ScanSessionController@print')->name('app.scanSessions.print');
        Route::get('/scan_sessions/{id}/payment', 'ScanSessionController@payment')->name('app.scanSessions.payment');
        Route::get('/scan_sessions/{id}/payment/status', 'ScanSessionController@status')->name('app.scanSessions.payment.status');
        Route::get('/scan_sessions/{id}/payment/request', 'ScanSessionController@requestPayment')->name('app.scanSessions.payment.request');

        ## Media Playlist
        Route::post('/mediaplaylist', 'MediaPlaylistController@store');

        /* Profile update */
        Route::post('saveprofile', array('as' => 'saveprofile', 'uses' => 'BioConnectController@saveprofile_database'));
        Route::post('/bioconnect/profile/password', 'BioConnectController@updatePassword')->name('bioconnect.profile.password');

        /* Group */
        Route::post('savediscussions', array('as' => 'savediscussions', 'uses' => 'GroupController@save_discussions_database'));

    }); // end subscriber middleware

});

/*** Admin ***/

Route::prefix('admin')->namespace('Admin')->middleware(['verified', 'auth.admin'])->group(function () {
    Route::get('/', 'HomeController@index');
    Route::get('/email', 'HomeController@email');
    Route::post('/email/send', 'HomeController@sendEmail');

    ## Pairs
    Route::get('pairs', 'PairController@index');
    Route::get('pairs/bio', 'PairController@bio');
    Route::get('pairs/chakra', 'PairController@chakra');
    Route::get('pairs/datatables', 'PairController@datatables');
    Route::get('pairs/import', 'PairController@import');
    Route::post('pairs/parse', 'PairController@parse');

    ## Dr Goiz Pairs
    Route::get('dr_goiz_pairs', 'DrGoizPairController@index');

    ## Media
    Route::get('media', 'MediaController@index');
    Route::post('media', 'MediaController@store');
    Route::get('media/datatables', 'MediaController@datatables');
    Route::post('media/update/{id}', 'MediaController@update');
    Route::get('media/delete/{id}', 'MediaController@destroy');

    ## MediaPlaylist
    Route::post('mediaplaylist', 'MediaPlaylistController@store');

    ## Model Labels
    Route::get('model_labels', 'ModelLabelController@index');
    Route::get('model_labels/body_scan', 'ModelLabelController@bodyscan');
    Route::get('model_labels/chakra_scan', 'ModelLabelController@chakrascan');

    ## Orders
    Route::get('orders', 'OrderController@index')->name('admin.orders');

    ## Plans
    Route::get('plans', 'PlanController@index')->name('admin.plans');
    Route::get('plans/{id}/subscribers', 'PlanController@subscribers')->name('admin.plans.subscribers.index');

    ## Products
    Route::get('products', 'ProductController@index')->name('admin.products');

    ## Playlist
    Route::get('playlist', 'PlaylistController@index');
    Route::get('playlist/datatables', 'PlaylistController@datatables');
    Route::post('playlist', 'PlaylistController@store');
    Route::post('playlist/update/{id}', 'PlaylistController@update');
    Route::get('playlist/delete/{id}', 'PlaylistController@destroy');
    Route::get('playlist/media/{id}', 'PlaylistController@getMedia');
    Route::get('playlist/allmedia/{id}', 'PlaylistController@allMedia');

    ## Orders
Route::get('subscriptions', 'SubscriptionController@index')->name('admin.subscriptions');

    ## Users
    Route::get('users', 'UserController@index');
    Route::get('users/{id}/subscriptions', 'UserController@subscriptions');

    ## Contact Messages
    Route::get('contact', 'ContactController@index')->name('admin.contact');
    Route::get('contact/datatables', 'ContactController@datatables')->name('admin.contact.datatables');
    Route::post('contact/{id}/read', 'ContactController@markRead')->name('admin.contact.mark-read');
    Route::get('contact/mark-all-read', 'ContactController@markAllRead')->name('admin.contact.mark-all-read');
    Route::delete('contact/{id}', 'ContactController@destroy')->name('admin.contact.destroy');
});

/*** Admin ***/
