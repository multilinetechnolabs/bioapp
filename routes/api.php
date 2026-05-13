<?php

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

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

Route::namespace('Api')->group(function () {
    Route::get('/', function (Request $request) {
        return $request->user();
    });

    Route::namespace('V1')->prefix('v1')->group(function () {
        ## Activities
        Route::get('activities', 'ActivityController@index')->name('api.v1.activities.index');
        Route::post('activities', 'ActivityController@store')->name('api.v1.activities.store');
        Route::get('activities/{id}', 'ActivityController@show')->name('api.v1.activities.show');
        Route::put('activities/{id}', 'ActivityController@update')->name('api.v1.activities.update');
        Route::delete('activities/{id}', 'ActivityController@destroy')->name('api.v1.activities.destroy');

        ## Activity Categories
        Route::get('activity_categories', 'Activity\CategoryController@index')->name('api.v1.activity_categories.index');
        Route::post('activity_categories', 'Activity\CategoryController@store')->name('api.v1.activity_categories.store');
        Route::get('activity_categories/{id}', 'Activity\CategoryController@show')->name('api.v1.activity_categories.show');
        Route::put('activity_categories/{id}', 'Activity\CategoryController@update')->name('api.v1.activity_categories.update');
        Route::delete('activity_categories/{id}', 'Activity\CategoryController@destroy')->name('api.v1.activity_categories.destroy');

        ## Admin
        Route::post('admin/mail', 'AdminController@mail');

        ## Bookmarks
        Route::get('bookmarks', 'BookmarkController@index');
        Route::post('bookmarks', 'BookmarkController@store');
        Route::get('bookmarks/{id}', 'BookmarkController@show');
        Route::put('bookmarks/{id}', 'BookmarkController@update');
        Route::delete('bookmarks/{id}', 'BookmarkController@destroy');

        ## Clients
        Route::get('clients', 'ClientController@index');
        Route::get('clients/datatables', 'ClientController@datatables');
        Route::post('clients', 'ClientController@store');
        Route::get('clients/{id}', 'ClientController@show');
        Route::put('clients/{id}', 'ClientController@update');
        Route::delete('clients/{id}', 'ClientController@destroy');

        ## Clients - Client Pairs
        Route::get('clients/{client_id}/client_pairs', 'Client\ClientPairController@index');
        Route::post('clients/{client_id}/client_pairs', 'Client\ClientPairController@store');
        Route::get('clients/{client_id}/client_pairs/{id}', 'Client\ClientPairController@show');
        Route::put('clients/{client_id}/client_pairs/{id}', 'Client\ClientPairController@update');
        Route::delete('clients/{client_id}/client_pairs/{id}', 'Client\ClientPairController@destroy');

        ## Clients - Medical Notes
        Route::get('clients/{client_id}/medical_notes', 'Client\MedicalNoteController@index');
        Route::post('clients/{client_id}/medical_notes', 'Client\MedicalNoteController@store');
        Route::get('clients/{client_id}/medical_notes/{id}', 'Client\MedicalNoteController@show');
        Route::put('clients/{client_id}/medical_notes/{id}', 'Client\MedicalNoteController@update');
        Route::delete('clients/{client_id}/medical_notes/{id}', 'Client\MedicalNoteController@destroy');

        ## Clients - Consent Forms
        Route::get('clients/{client_id}/consent_forms', 'Client\ConsentFormController@index');
        Route::post('clients/{client_id}/consent_forms', 'Client\ConsentFormController@store');
        Route::get('clients/{client_id}/consent_forms/{id}', 'Client\ConsentFormController@show');
        Route::put('clients/{client_id}/consent_forms/{id}', 'Client\ConsentFormController@update');
        Route::delete('clients/{client_id}/consent_forms/{id}', 'Client\ConsentFormController@destroy');

        ## Clients - Scan Sessions
        Route::get('clients/{client_id}/scan_sessions', 'Client\ScanSessionController@index');
        Route::post('clients/{client_id}/scan_sessions', 'Client\ScanSessionController@store');
        Route::get('clients/{client_id}/scan_sessions/latest', 'Client\ScanSessionController@latest');
        Route::get('clients/{client_id}/scan_sessions/{id}', 'Client\ScanSessionController@show');
        Route::put('clients/{client_id}/scan_sessions/{id}', 'Client\ScanSessionController@update');
        Route::delete('clients/{client_id}/scan_sessions/{id}', 'Client\ScanSessionController@destroy');

        ## Clients - Pairs
        Route::get('clients/{client_id}/pairs', 'Client\PairController@index');
        Route::get('clients/{client_id}/pairs/{id}', 'Client\PairController@show');

        ## Discussions
        Route::get('discussions', 'DiscussionController@index');
        Route::post('discussions', 'DiscussionController@store');
        Route::get('discussions/{id}', 'DiscussionController@show');
        Route::put('discussions/{id}', 'DiscussionController@update');
        Route::delete('discussions/{id}', 'DiscussionController@destroy');

        ## Discussions - Comments
        Route::get('discussions/{discussion_id}/comments', 'Discussion\CommentController@index');
        Route::post('discussions/{discussion_id}/comments', 'Discussion\CommentController@store');
        Route::get('discussions/{discussion_id}/comments/{id}', 'Discussion\CommentController@show');
        Route::put('discussions/{discussion_id}/comments/{id}', 'Discussion\CommentController@update');
        Route::delete('discussions/{discussion_id}/comments/{id}', 'Discussion\CommentController@destroy');

        ## Logos
        Route::get('logos', 'LogoController@index')->name('api.v1.logos.index');
        Route::post('logos', 'LogoController@store')->name('api.v1.logos.store');
        Route::get('logos/{id}', 'LogoController@show')->name('api.v1.logos.show');
        Route::put('logos/{id}', 'LogoController@update')->name('api.v1.logos.update');
        Route::delete('logos/{id}', 'LogoController@destroy')->name('api.v1.logos.destroy');

        ## MediaPlaylists
        Route::get('media_playlists', 'MediaPlaylistController@index');
        Route::post('media_playlists', 'MediaPlaylistController@store');
        Route::get('media_playlists/{id}', 'MediaPlaylistController@show');
        Route::put('media_playlists/{id}', 'MediaPlaylistController@update');
        Route::delete('media_playlists/{id}', 'MediaPlaylistController@destroy');

        ## Medias
        Route::get('medias', 'MediaController@index')->name('api.v1.medias.index');
        Route::post('medias', 'MediaController@store')->name('api.v1.medias.store');
        Route::get('medias/{id}', 'MediaController@show')->name('api.v1.medias.show');
        Route::put('medias/{id}', 'MediaController@update')->name('api.v1.medias.update');
        Route::delete('medias/{id}', 'MediaController@destroy')->name('api.v1.medias.destroy');

        ## Media - MediaPlaylists
        Route::get('medias/{media_id}/media_playlists', 'Media\MediaPlaylistController@index');
        Route::get('medias/{media_id}/media_playlists/{id}', 'Media\MediaPlaylistController@show');

        ## Media - Playlists
        Route::get('medias/{media_id}/playlists', 'Media\PlaylistController@index');
        Route::get('medias/{media_id}/playlists/{id}', 'Media\PlaylistController@show');

        ## Model Labels
        Route::get('model_labels', 'ModelLabelController@index')->name('api.v1.modelLabels.index');
        Route::get('model_labels/datatables', 'ModelLabelController@datatables')->name('api.v1.modelLabels.datatables');
        Route::post('model_labels', 'ModelLabelController@store')->name('api.v1.modelLabels.store');
        Route::get('model_labels/{id}', 'ModelLabelController@show')->name('api.v1.modelLabels.show');
        Route::put('model_labels/{id}', 'ModelLabelController@update')->name('api.v1.modelLabels.update');
        Route::delete('model_labels/{id}', 'ModelLabelController@destroy')->name('api.v1.modelLabels.destroy');

        ## Orders
        Route::get('orders', 'OrderController@index')->name('api.v1.orders.index');
        Route::get('orders/datatables', 'OrderController@datatables')->name('api.v1.orders.datatables');
        Route::post('orders', 'OrderController@store')->name('api.v1.orders.store');
        Route::get('orders/{id}', 'OrderController@show')->name('api.v1.orders.show');
        Route::put('orders/{id}', 'OrderController@update')->name('api.v1.orders.update');
        Route::delete('orders/{id}', 'OrderController@destroy')->name('api.v1.orders.destroy');

        ## Scan Session Pairs
        Route::get('scan_session_pairs', 'ScanSessionPairController@index');
        Route::get('scan_session_pairs/{id}', 'ScanSessionPairController@show');

        ## Scan Sessions
        Route::get('scan_sessions', 'ScanSessionController@index');
        Route::post('scan_sessions', 'ScanSessionController@store');
        Route::get('scan_sessions/active', 'ScanSessionController@active');
        Route::get('scan_sessions/{id}', 'ScanSessionController@show');
        Route::get('scan_sessions/{id}/mail', 'ScanSessionController@mail');
        Route::put('scan_sessions/{id}', 'ScanSessionController@update');
        Route::delete('scan_sessions/{id}', 'ScanSessionController@destroy');

        ## Scan Sessions - Pairs
        Route::get('scan_sessions/{scan_session_id}/pairs', 'ScanSession\PairController@index');
        Route::get('scan_sessions/{scan_session_id}/pairs/{id}', 'ScanSession\PairController@show');

        ## Scan Sessions - Scan Session Pairs
        Route::get('scan_sessions/{scan_session_id}/scan_session_pairs', 'ScanSession\ScanSessionPairController@index');
        Route::post('scan_sessions/{scan_session_id}/scan_session_pairs', 'ScanSession\ScanSessionPairController@store');
        Route::get('scan_sessions/{scan_session_id}/scan_session_pairs/{id}', 'ScanSession\ScanSessionPairController@show');
        Route::put('scan_sessions/{scan_session_id}/scan_session_pairs/{id}', 'ScanSession\ScanSessionPairController@update');
        Route::delete('scan_sessions/{scan_session_id}/scan_session_pairs/{id}', 'ScanSession\ScanSessionPairController@destroy');

        ## Dr Goiz Pairs
        Route::get('dr_goiz_pairs', 'DrGoizPairController@index')->name('api.v1.dr_goiz_pairs.index');
        Route::get('dr_goiz_pairs/datatables', 'DrGoizPairController@datatables')->name('api.v1.dr_goiz_pairs.datatables');
        Route::post('dr_goiz_pairs', 'DrGoizPairController@store')->name('api.v1.dr_goiz_pairs.store');
        Route::get('dr_goiz_pairs/{id}', 'DrGoizPairController@show')->name('api.v1.dr_goiz_pairs.show');
        Route::put('dr_goiz_pairs/{id}', 'DrGoizPairController@update')->name('api.v1.dr_goiz_pairs.update');
        Route::delete('dr_goiz_pairs/{id}', 'DrGoizPairController@destroy')->name('api.v1.dr_goiz_pairs.destroy');

        ## Pairs
        Route::get('pairs', 'PairController@index')->name('api.v1.pairs.index');
        Route::get('pairs/datatables', 'PairController@datatables')->name('api.v1.pairs.datatables');
        Route::post('pairs', 'PairController@store')->name('api.v1.pairs.store');
        Route::get('pairs/{id}', 'PairController@show')->name('api.v1.pairs.show');
        Route::put('pairs/{id}', 'PairController@update')->name('api.v1.pairs.update');
        Route::delete('pairs/{id}', 'PairController@destroy')->name('api.v1.pairs.destroy');

        ## Payments
        Route::get('payments', 'PaymentController@index')->name('api.v1.payments.index');
        Route::post('payments', 'PaymentController@store')->name('api.v1.payments.store');
        Route::get('payments/status', 'PaymentController@status')->name('api.v1.payments.status');
        Route::get('payments/{id}', 'PaymentController@show')->name('api.v1.payments.show');
        Route::put('payments/{id}', 'PaymentController@update')->name('api.v1.payments.update');
        Route::delete('payments/{id}', 'PaymentController@destroy')->name('api.v1.payments.destroy');

        ## Plans
        Route::get('plans', 'PlanController@index')->name('api.v1.plans.index');
        Route::get('plans/datatables', 'PlanController@datatables')->name('api.v1.plans.datatables');
        Route::post('plans', 'PlanController@store')->name('api.v1.plans.store');
        Route::get('plans/{id}', 'PlanController@show')->name('api.v1.plans.show');
        Route::put('plans/{id}', 'PlanController@update')->name('api.v1.plans.update');
        Route::delete('plans/{id}', 'PlanController@destroy')->name('api.v1.plans.destroy');

        ## Plans - Subscribers(Users)
        Route::get('plans/{plan_id}/subscribers', 'Plan\SubscriberController@index');
        Route::get('plans/{plan_id}/subscribers/datatables', 'Plan\SubscriberController@datatables');
        Route::get('plans/{plan_id}/subscribers/{id}', 'Plan\SubscriberController@show');

        ## Plan - Subscriptions
        Route::get('plans/{plan_id}/subscriptions', 'Plan\SubscriptionController@index');
        Route::get('plans/{plan_id}/subscriptions/{id}', 'Plan\SubscriptionController@show');

        ## Playlists
        Route::get('playlists', 'PlaylistController@index')->name('api.v1.playlists.index');
        Route::post('playlists', 'PlaylistController@store')->name('api.v1.playlists.store');
        Route::get('playlists/{id}', 'PlaylistController@show')->name('api.v1.playlists.show');
        Route::put('playlists/{id}', 'PlaylistController@update')->name('api.v1.playlists.update');
        Route::delete('playlists/{id}', 'PlaylistController@destroy')->name('api.v1.playlists.destroy');

        ## Playlist - MediaPlaylists
        Route::get('playlists/{playlist_id}/media_playlists', 'Playlist\MediaPlaylistController@index');
        Route::post('playlists/{playlist_id}/media_playlists', 'Playlist\MediaPlaylistController@index');
        Route::get('playlists/{playlist_id}/media_playlists/{id}', 'Playlist\MediaPlaylistController@show');
        Route::put('playlists/{playlist_id}/media_playlists/{id}', 'Playlist\MediaPlaylistController@index');
        Route::delete('playlists/{playlist_id}/media_playlists/{id}', 'Playlist\MediaPlaylistController@destroy');

        ## Playlist - Medias
        Route::get('playlists/{playlist_id}/medias', 'Playlist\MediaController@index');
        Route::get('playlists/{playlist_id}/medias/{id}', 'Playlist\MediaController@show');
        Route::delete('playlists/{playlist_id}/medias/{id}', 'Playlist\MediaController@destroy');

        ## Products
        Route::get('products', 'ProductController@index')->name('api.v1.products.index');
        Route::get('products/datatables', 'ProductController@datatables')->name('api.v1.products.datatables');
        Route::post('products', 'ProductController@store')->name('api.v1.products.store');
        Route::get('products/{id}', 'ProductController@show')->name('api.v1.products.show');
        Route::put('products/{id}', 'ProductController@update')->name('api.v1.products.update');
        Route::delete('products/{id}', 'ProductController@destroy')->name('api.v1.products.destroy');

        ## Subscriptions
        Route::get('subscriptions', 'SubscriptionController@index')->name('api.v1.subscriptions.index');
        Route::get('subscriptions/datatables', 'SubscriptionController@datatables')->name('api.v1.subscriptions.datatables');
        Route::post('subscriptions', 'SubscriptionController@store')->name('api.v1.subscriptions.store');
        Route::get('subscriptions/{id}', 'SubscriptionController@show')->name('api.v1.subscriptions.show');
        Route::put('subscriptions/{id}', 'SubscriptionController@update')->name('api.v1.subscriptions.update');
        Route::delete('subscriptions/{id}', 'SubscriptionController@destroy')->name('api.v1.subscriptions.destroy');

        ## Users
        Route::get('users', 'UserController@index')->name('api.v1.users.index');
        Route::get('users/datatables', 'UserController@datatables')->name('api.v1.users.datatables');
        Route::post('users', 'UserController@store')->name('api.v1.users.store');
        Route::get('users/me', 'UserController@show')->name('api.v1.users.me');
        Route::put('users/me', 'UserController@update')->name('api.v1.users.me');
        Route::get('users/{id}', 'UserController@show')->name('api.v1.users.show');
        Route::put('users/{id}', 'UserController@update')->name('api.v1.users.update');
        Route::delete('users/{id}', 'UserController@destroy')->name('api.v1.users.destroy');

        ## User - Bookmarks
        Route::get('users/me/bookmarks', 'User\BookmarkController@index');
        Route::post('users/me/bookmarks', 'User\BookmarkController@store');
        Route::get('users/me/bookmarks/{id}', 'User\BookmarkController@show');
        Route::put('users/me/bookmarks/{id}', 'User\BookmarkController@update');
        Route::delete('users/me/bookmarks/{id}', 'User\BookmarkController@destroy');

        Route::get('users/{user_id}/bookmarks', 'User\BookmarkController@index');
        Route::post('users/{user_id}/bookmarks', 'User\BookmarkController@store');
        Route::get('users/{user_id}/bookmarks/{id}', 'User\BookmarkController@show');
        Route::put('users/{user_id}/bookmarks/{id}', 'User\BookmarkController@update');
        Route::delete('users/{user_id}/bookmarks/{id}', 'User\BookmarkController@destroy');

        ## User - Friends
        Route::get('users/me/friends', 'User\FriendController@index');
        Route::post('users/me/friends', 'User\FriendController@store');
        Route::get('users/me/friends/available', 'User\FriendController@available');
        Route::get('users/me/friends/{id}', 'User\FriendController@show');
        Route::delete('users/me/friends/{id}', 'User\FriendController@destroy');
        Route::get('users/me/friend_requests', 'User\FriendController@friend_requests');
        Route::put('users/me/friend_requests/{id}', 'User\FriendController@accept');
        Route::delete('users/me/friend_requests/{id}', 'User\FriendController@reject');
        Route::get('users/{user_id}/friends', 'User\FriendController@index');
        Route::get('users/{user_id}/friend_requests', 'User\FriendController@friend_requests');
        Route::post('users/{user_id}/friends', 'User\FriendController@store');
        Route::get('users/{user_id}/friends/available', 'User\FriendController@available');
        Route::get('users/{user_id}/friends/{id}', 'User\FriendController@show');
        Route::put('users/{user_id}/friends/{id}', 'User\FriendController@update');
        Route::delete('users/{user_id}/friends/{id}', 'User\FriendController@destroy');
        Route::put('users/{user_id}/friends/{id}', 'User\FriendController@accept');
        Route::delete('users/{user_id}/friends/{id}', 'User\FriendController@reject');

        ## User - Orders
        Route::get('users/me/orders', 'User\OrderController@index');
        Route::get('users/me/orders/datatables', 'User\OrderController@datatables');
        Route::get('users/{user_id}/orders', 'User\OrderController@index');
        Route::get('users/{user_id}/orders/datatables', 'User\OrderController@datatables');
        Route::get('users/me/orders/{id}', 'User\OrderController@show');
        Route::get('users/{user_id}/orders/{id}', 'User\OrderController@show');

        ## User - Payments
        Route::get('users/me/payments', 'User\PaymentController@index');
        Route::get('users/me/payments/datatables', 'User\PaymentController@datatables');
        Route::get('users/{user_id}/payments', 'User\PaymentController@index');
        Route::get('users/{user_id}/payments/datatables', 'User\PaymentController@datatables');
        Route::get('users/me/payments/{id}', 'User\PaymentController@show');
        Route::get('users/{user_id}/payments/{id}', 'User\PaymentController@show');

        ## User - Plans
        Route::get('users/me/plans', 'User\PlanController@index');
        Route::get('users/{user_id}/plans', 'User\PlanController@index');
        Route::get('users/me/plans/{id}', 'User\PlanController@show');
        Route::get('users/{user_id}/plans/{id}', 'User\PlanController@show');

        ## User - Subscriptions
        Route::get('users/me/subscriptions', 'User\SubscriptionController@index');
        Route::get('users/me/subscriptions/datatables', 'User\SubscriptionController@datatables');
        Route::get('users/{user_id}/subscriptions', 'User\SubscriptionController@index');
        Route::post('users/{user_id}/subscriptions', 'User\SubscriptionController@store');
        Route::get('users/{user_id}/subscriptions/datatables', 'User\SubscriptionController@datatables');
        Route::get('users/me/subscriptions/{id}', 'User\SubscriptionController@show');
        Route::get('users/{user_id}/subscriptions/{id}', 'User\SubscriptionController@show');
        Route::put('users/{user_id}/subscriptions/{id}', 'User\SubscriptionController@update');
        Route::delete('users/{user_id}/subscriptions/{id}', 'User\SubscriptionController@destroy');

        Route::fallback(function () {
            $status_code = Response::HTTP_NOT_FOUND;

            $response['success'] = false;
            $response['error'] = [
                'code' => $status_code,
                'message' => 'Route Not Found'
            ];

            return response()->json($response, $status_code);
        });
    });

    Route::fallback(function () {
        $status_code = Response::HTTP_NOT_FOUND;

        $response['success'] = false;
        $response['error'] = [
            'code' => $status_code,
            'message' => 'Route Not Found'
        ];

        return response()->json($response, $status_code);
    });
});
