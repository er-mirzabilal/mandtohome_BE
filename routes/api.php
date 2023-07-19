<?php


use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RateListController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

use App\Enums\Permission;
use App\Http\Controllers\AbusiveReportController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\WithdrawController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/token', [UserController::class, 'token']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/forget-password', [UserController::class, 'forgetPassword']);
Route::post('/verify-forget-password-token', [UserController::class, 'verifyForgetPasswordToken']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);
Route::post('/contact-us', [UserController::class, 'contactAdmin']);
Route::post('/social-login-token', [UserController::class, 'socialLogin']);
Route::post('/send-otp-code', [UserController::class, 'sendOtpCode']);
Route::post('/verify-otp-code', [UserController::class, 'verifyOtpCode']);
Route::post('/otp-login', [UserController::class, 'otpLogin']);
Route::get('top-authors', [AuthorController::class, 'topAuthor']);
Route::get('top-manufacturers', [ManufacturerController::class, 'topManufacturer']);
Route::get('popular-products', [ProductController::class, 'popularProducts']);

Route::get('rate-list','App\Http\Controllers\RateListController@index');
Route::apiResource('rate-list', RateListController::class);
Route::apiResource('products', ProductController::class, [
    'only' => ['index', 'show'],
]);
Route::apiResource('types', TypeController::class, [
    'only' => ['index', 'show'],
]);
Route::apiResource('attachments', AttachmentController::class, [
    'only' => ['index', 'show'],
]);
Route::apiResource('categories', CategoryController::class, [
    'only' => ['index', 'show'],
]);
Route::apiResource('tags', TagController::class, [
    'only' => ['index', 'show'],
]);

Route::apiResource('coupons', CouponController::class, [
    'only' => ['index', 'show'],
]);

Route::post('coupons/verify', [CouponController::class, 'verify']);

Route::apiResource('order-status', OrderStatusController::class, [
    'only' => ['index', 'show'],
]);

Route::apiResource('attributes', AttributeController::class, [
    'only' => ['index', 'show'],
]);

Route::apiResource('shops', ShopController::class, [
    'only' => ['index', 'show'],
]);

Route::apiResource('settings', SettingsController::class, [
    'only' => ['index'],
]);

Route::apiResource('reviews', ReviewController::class, [
    'only' => ['index', 'show'],
]);
Route::apiResource('questions', QuestionController::class, [
    'only' => ['index', 'show'],
]);
Route::apiResource('feedbacks', FeedbackController::class, [
    'only' => ['index', 'show'],
]);
Route::apiResource('authors', AuthorController::class, [
    'only' => ['index', 'show'],
]);

Route::apiResource('manufacturers', ManufacturerController::class, [
    'only' => ['index', 'show'],
]);

Route::post('orders/checkout/verify', [CheckoutController::class, 'verify']);

Route::apiResource('orders', OrderController::class, [
    'only' => ['show', 'store'],
]);

Route::group(['middleware' => ['can:' . Permission::CUSTOMER, 'auth:sanctum']], function () {
    Route::apiResource('orders', OrderController::class, [
        'only' => ['index'],
    ]);
    Route::apiResource('reviews', ReviewController::class, [
        'only' => ['store', 'update']
    ]);
    Route::apiResource('questions', QuestionController::class, [
        'only' => ['store'],
    ]);
    Route::apiResource('feedbacks', FeedbackController::class, [
        'only' => ['store'],
    ]);
    Route::apiResource('abusive_reports', AbusiveReportController::class, [
        'only' => ['store'],
    ]);
    Route::get('my-questions', [QuestionController::class, 'myQuestions']);
    Route::get('my-reports', [AbusiveReportController::class, 'myReports']);
    Route::post('wishlists/toggle', [WishlistController::class, 'toggle']);
    Route::apiResource('wishlists', WishlistController::class, [
        'only' => ['index', 'store', 'destroy'],
    ]);
    Route::get('wishlists/in_wishlist/{product_id}', [WishlistController::class, 'in_wishlist']);
    Route::get('my-wishlists', [ProductController::class, 'myWishlists']);
    Route::get('orders/tracking-number/{tracking_number}', 'App\Http\Controllers\OrderController@findByTrackingNumber');
    Route::apiResource('attachments', AttachmentController::class, [
        'only' => ['store', 'update', 'destroy'],
    ]);
    Route::get('me', [UserController::class, 'me']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::post('/update-contact', [UserController::class, 'updateContact']);
    Route::apiResource('address', AddressController::class, [
        'only' => ['destroy'],
    ]);
    Route::apiResource(
        'refunds',
        RefundController::class,
        [
            'only' => ['index', 'store', 'show'],
        ]
    );

    Route::get('downloads', [DownloadController::class, 'fetchDownloadableFiles']);
    Route::post('downloads/digital_file', [DownloadController::class, 'generateDownloadableUrl']);

    Route::get('/followed-shops-popular-products', [ShopController::class, 'followedShopsPopularProducts']);
    Route::get('/followed-shops', [ShopController::class, 'userFollowedShops']);
    Route::get('/follow-shop', [ShopController::class, 'userFollowedShop']);
    Route::post('/follow-shop', [ShopController::class, 'handleFollowShop']);

});


Route::group(
    ['middleware' => ['permission:' . Permission::STAFF . '|' . Permission::STORE_OWNER, 'auth:sanctum']],
    function () {
        Route::get('analytics', [AnalyticsController::class, 'analytics']);
        Route::apiResource('products', ProductController::class, [
            'only' => ['store', 'update', 'destroy'],
        ]);
        Route::apiResource('attributes', AttributeController::class, [
            'only' => ['store', 'update', 'destroy'],
        ]);
        Route::apiResource('attribute-values', AttributeValueController::class, [
            'only' => ['store', 'update', 'destroy'],
        ]);
        Route::apiResource('orders', OrderController::class, [
            'only' => ['update', 'destroy'],
        ]);
        Route::apiResource('customers',CustomerController::class);
        // Route::get('popular-products', [AnalyticsController::class, 'popularProducts']);
        // Route::get('shops/refunds', 'Marvel\Http\Controllers\ShopController@refunds');

        Route::apiResource('questions', QuestionController::class, [
            'only' => ['update'],
        ]);
        Route::apiResource('authors', AuthorController::class, [
            'only' => ['store'],
        ]);
        Route::apiResource('manufacturers', ManufacturerController::class, [
            'only' => ['store'],
        ]);

        Route::get('export-order-url/{shop_id?}', 'App\Http\Controllers\OrderController@exportOrderUrl');
    }
);
Route::group(
    ['middleware' => ['permission:' . Permission::STAFF . '|' . Permission::STORE_OWNER . '|' . Permission::SUPER_ADMIN, 'auth:sanctum']],
    function () {
        Route::apiResource('users', UserController::class)->only('update');
    });
Route::post('import-products', [ProductController::class, 'importProducts']);
Route::post('import-variation-options', [ProductController::class, 'importVariationOptions']);
Route::get('export-products/{shop_id}', [ProductController::class, 'exportProducts']);
Route::get('export-variation-options/{shop_id}', [ProductController::class, 'exportVariableOptions']);
Route::post('import-attributes', [AttributeController::class, 'importAttributes']);
Route::get('export-attributes/{shop_id}', [AttributeController::class, 'exportAttributes']);

Route::group(
    ['middleware' => ['permission:' . Permission::STORE_OWNER, 'auth:sanctum']],
    function () {
        Route::apiResource('shops', ShopController::class, [
            'only' => ['store', 'update', 'destroy'],
        ]);
        Route::apiResource('withdraws', WithdrawController::class, [
            'only' => ['store', 'index', 'show'],
        ]);
        Route::post('staffs', [ShopController::class, 'addStaff']);
        Route::delete('staffs/{id}', [ShopController::class, 'deleteStaff']);
        Route::get('staffs', [UserController::class, 'staffs']);
        Route::get('my-shops', [ShopController::class, 'myShops']);
    }
);

Route::group(['middleware' => ['permission:' . Permission::SUPER_ADMIN ,'auth:sanctum']], function () {
    Route::apiResource('types', TypeController::class, [
        'only' => ['store', 'update', 'destroy'],
    ]);
    Route::apiResource('withdraws', WithdrawController::class, [
        'only' => ['update', 'destroy'],
    ]);
    Route::apiResource('categories', CategoryController::class, [
        'only' => ['store', 'update', 'destroy'],
    ]);
    Route::apiResource('tags', TagController::class, [
        'only' => ['store', 'update', 'destroy'],
    ]);
    Route::apiResource('coupons', CouponController::class, [
        'only' => ['store', 'update', 'destroy'],
    ]);
    Route::apiResource('order-status', OrderStatusController::class, [
        'only' => ['store', 'update', 'destroy'],
    ]);

    Route::apiResource('reviews', ReviewController::class, [
        'only' => ['destroy']
    ]);
    Route::apiResource('questions', QuestionController::class, [
        'only' => ['destroy'],
    ]);
    Route::apiResource('feedbacks', QuestionController::class, [
        'only' => ['update', 'destroy'],
    ]);
    Route::apiResource('abusive_reports', AbusiveReportController::class, [
        'only' => ['index', 'show', 'update', 'destroy'],
    ]);
    Route::post('abusive_reports/accept', [AbusiveReportController::class, 'accept']);
    Route::post('abusive_reports/reject', [AbusiveReportController::class, 'reject']);
    Route::apiResource('settings', SettingsController::class, [
        'only' => ['store'],
    ]);
    Route::apiResource('users', UserController::class)->except('update');
    Route::apiResource('authors', AuthorController::class, [
        'only' => ['update', 'destroy'],
    ]);
    Route::apiResource('manufacturers', ManufacturerController::class, [
        'only' => ['update', 'destroy'],
    ]);
    Route::post('users/block-user', [UserController::class, 'banUser']);
    Route::post('users/unblock-user', [UserController::class, 'activeUser']);
    Route::apiResource('taxes', TaxController::class);
    Route::apiResource('shippings', ShippingController::class);
    Route::post('approve-shop', [ShopController::class, 'approveShop']);
    Route::post('disapprove-shop', [ShopController::class, 'disApproveShop']);
    Route::post('approve-withdraw', [WithdrawController::class, 'approveWithdraw']);
    Route::post('add-points', [UserController::class, 'addPoints']);
    Route::post('users/make-admin', [UserController::class, 'makeOrRevokeAdmin']);
    Route::apiResource(
        'refunds',
        RefundController::class,
        [
            'only' => ['destroy', 'update'],
        ]
    );
});


Route::get(
    'download_url/token/{token}',
    [DownloadController::class, 'downloadFile']
)->name('download_url.token');

Route::get(
    'export-order/token/{token}',
    [OrderController::class, 'exportOrder']
)->name('export_order.token');
Route::post(
    'subscribe-to-newsletter',
    [UserController::class, 'subscribeToNewsletter']
)->name('subscribeToNewsletter');

