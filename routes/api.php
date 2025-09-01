<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\REST\V1 as RESTV1;
use App\Http\Controllers\REST\Errors;

## Base API Route
Route::post('/', [RESTV1\Home::class, 'index']);

## Authorization
Route::prefix('auth')->group(function () {
    Route::post('account', [RESTV1\Auth\Account::class, 'index']);
});

## My Data
Route::prefix('my')->middleware('auth:bearer')->group(function () {
    // ### Get my data
    Route::get('/', [RESTV1\My\Data::class, 'index']);

    // ### Get my privileges
    Route::get('privileges', [RESTV1\My\Privileges::class, 'index']);
});

## Manage
Route::prefix('manage')->group(function () {

    Route::prefix('business')->group(function () {
        Route::get('/', [RESTV1\Manage\Business\Get::class, 'index']);
        Route::get('summary', [RESTV1\Manage\Business\Summary\Get::class, 'index']);
        Route::get('{id}', [RESTV1\Manage\Business\Get::class, 'index']);
        Route::post('/', [RESTV1\Manage\Business\Insert::class, 'index']);
        Route::put('{id}', [RESTV1\Manage\Business\Update::class, 'index']);
        Route::delete('{id}', [RESTV1\Manage\Business\Delete::class, 'index']);
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [RESTV1\Manage\Customers\Get::class, 'index']);
        Route::get('{id}', [RESTV1\Manage\Customers\Get::class, 'index']);
        Route::post('/', [RESTV1\Manage\Customers\Insert::class, 'index']);
        // Route::put('{id}', [RESTV1\Manage\Business\Update::class, 'index']);
        // Route::delete('{id}', [RESTV1\Manage\Business\Delete::class, 'index']);
    });

    Route::prefix('customer-category')->group(function () {
        Route::get('/', [RESTV1\Manage\CustomerCategory\Get::class, 'index']);
        Route::get('{id}', [RESTV1\Manage\CustomerCategory\Get::class, 'index']);
        Route::post('/', [RESTV1\Manage\CustomerCategory\Insert::class, 'index']);
        Route::put('{id}', [RESTV1\Manage\CustomerCategory\Update::class, 'index']);
        // Route::delete('{id}', [RESTV1\Manage\Business\Delete::class, 'index']);
    });

    Route::prefix('outlets')->group(function () {
        Route::get('/', [RESTV1\Manage\Outlets\Get::class, 'index']);
        Route::get('{id}', [RESTV1\Manage\Outlets\Get::class, 'index']);
        Route::post('/', [RESTV1\Manage\Outlets\Insert::class, 'index']);
        // Route::put('{id}', [RESTV1\Manage\Position\Update::class, 'index']);
        // Route::delete('{id}', [RESTV1\Manage\Position\Delete::class, 'index']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [RESTV1\Manage\Categories\Get::class, 'index']);
        Route::get('{id}', [RESTV1\Manage\Categories\Get::class, 'index']);
        Route::post('/', [RESTV1\Manage\Categories\Insert::class, 'index']);
        // Route::put('{id}', [RESTV1\Manage\Position\Update::class, 'index']);
        // Route::delete('{id}', [RESTV1\Manage\Position\Delete::class, 'index']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [RESTV1\Manage\Products\Get::class, 'index']);
        Route::get('{id}', [RESTV1\Manage\Products\Get::class, 'index']);
        Route::post('/', [RESTV1\Manage\Products\Insert::class, 'index']);
        // Route::put('{id}', [RESTV1\Manage\Position\Update::class, 'index']);
        // Route::delete('{id}', [RESTV1\Manage\Position\Delete::class, 'index']);
    });

    Route::prefix('units')->group(function () {
        Route::get('/', [RESTV1\Manage\Units\Get::class, 'index']);
        Route::get('{id}', [RESTV1\Manage\Units\Get::class, 'index']);
        Route::post('/', [RESTV1\Manage\Units\Insert::class, 'index']);
        // Route::put('{id}', [RESTV1\Manage\Position\Update::class, 'index']);
        // Route::delete('{id}', [RESTV1\Manage\Position\Delete::class, 'index']);
    });

    Route::prefix('promos')->group(function () {
        Route::get('/', [RESTV1\Manage\Promos\Get::class, 'index']);
        Route::get('{id}', [RESTV1\Manage\Promos\Get::class, 'index']);
        Route::post('/', [RESTV1\Manage\Promos\Insert::class, 'index']);
        // Route::put('{id}', [RESTV1\Manage\Position\Update::class, 'index']);
        // Route::delete('{id}', [RESTV1\Manage\Position\Delete::class, 'index']);
    });

    Route::prefix('payment_methods')->group(function () {
        Route::get('/', [RESTV1\Manage\PaymentMethods\Get::class, 'index']);
        Route::get('{id}', [RESTV1\Manage\PaymentMethods\Get::class, 'index']);
        Route::post('/', [RESTV1\Manage\PaymentMethods\Insert::class, 'index']);
        // Route::put('{id}', [RESTV1\Manage\Position\Update::class, 'index']);
        // Route::delete('{id}', [RESTV1\Manage\Position\Delete::class, 'index']);
    });
});


// Route::prefix('manage')->middleware('auth:bearer')->group(function () {

//     Route::prefix('position')->group(function () {
//         Route::get('/', [RESTV1\Manage\Position\Get::class, 'index']);
//         Route::get('{id}', [RESTV1\Manage\Position\Get::class, 'index']);
//         Route::post('/', [RESTV1\Manage\Position\Insert::class, 'index']);
//         Route::put('{id}', [RESTV1\Manage\Position\Update::class, 'index']);
//         Route::delete('{id}', [RESTV1\Manage\Position\Delete::class, 'index']);
//     });

//     Route::prefix('account')->group(function () {
//         Route::get('/', [RESTV1\Manage\Account\Get::class, 'index']);
//         Route::get('{id}', [RESTV1\Manage\Account\Get::class, 'index']);
//         Route::post('/', [RESTV1\Manage\Account\Insert::class, 'index']);
//         Route::put('{id}', [RESTV1\Manage\Account\Update::class, 'index']);
//         Route::delete('{id}', [RESTV1\Manage\Account\Delete::class, 'index']);
//     });

//     Route::prefix('disposition')->group(function () {
//         Route::get('/', [RESTV1\Manage\Disposition\Get::class, 'index']);
//         Route::get('{id}', [RESTV1\Manage\Disposition\Get::class, 'index']);
//         Route::post('/', [RESTV1\Manage\Disposition\Insert::class, 'index']);
//         Route::put('{id}', [RESTV1\Manage\Disposition\Update::class, 'index']);
//         Route::delete('{id}', [RESTV1\Manage\Disposition\Delete::class, 'index']);
//     });

//     Route::prefix('mail_source')->group(function () {
//         Route::get('/', [RESTV1\Manage\MailSource\Get::class, 'index']);
//         Route::get('{id}', [RESTV1\Manage\MailSource\Get::class, 'index']);
//         Route::post('/', [RESTV1\Manage\MailSource\Insert::class, 'index']);
//         Route::put('{id}', [RESTV1\Manage\MailSource\Update::class, 'index']);
//         Route::delete('{id}', [RESTV1\Manage\MailSource\Delete::class, 'index']);
//     });

//     Route::prefix('incoming_mail')->group(function () {
//         Route::get('/', [RESTV1\Manage\IncomingMail\Get::class, 'index']);
//         Route::get('{id}', [RESTV1\Manage\IncomingMail\Get::class, 'index']);
//         Route::post('/', [RESTV1\Manage\IncomingMail\Insert::class, 'index']);
//         Route::put('{id}', [RESTV1\Manage\IncomingMail\Update::class, 'index']);
//         Route::delete('{id}', [RESTV1\Manage\IncomingMail\Delete::class, 'index']);

//         Route::prefix('{id}/disposition')->group(function () {
//             Route::get('/', [RESTV1\Manage\IncomingMail\Disposition\Get::class, 'index']);
//             Route::get('{disp_id}', [RESTV1\Manage\IncomingMail\Disposition\Get::class, 'index']);
//             Route::put('/', [RESTV1\Manage\IncomingMail\Disposition\Update::class, 'index']);
//         });
//     });

//     Route::prefix('outcoming_mail')->group(function () {
//         // Route::get('/', [RESTV1\Manage\OutcomingMail\Get::class, 'index']);
//         // Route::get('{id}', [RESTV1\Manage\OutcomingMail\Get::class, 'index']);
//         Route::put('/', [RESTV1\Manage\OutcomingMail\Update::class, 'index']);
//     });
// });

Route::prefix('transactions')->group(function () {

    Route::prefix('calculate')->group(function () {
        // Route::get('/', [RESTV1\Manage\Position\Get::class, 'index']);
        // Route::get('{id}', [RESTV1\Manage\Position\Get::class, 'index']);
        Route::post('/', [RESTV1\Transactions\Calculate\Insert::class, 'index']);
        // Route::put('{id}', [RESTV1\Manage\Position\Update::class, 'index']);
        // Route::delete('{id}', [RESTV1\Manage\Position\Delete::class, 'index']);
    });

    Route::prefix('execute')->group(function () {
        // Route::get('/', [RESTV1\Manage\Position\Get::class, 'index']);
        // Route::get('{id}', [RESTV1\Manage\Position\Get::class, 'index']);
        Route::post('/', [RESTV1\Transactions\Execute\Insert::class, 'index']);
        // Route::put('{id}', [RESTV1\Manage\Position\Update::class, 'index']);
        // Route::delete('{id}', [RESTV1\Manage\Position\Delete::class, 'index']);
    });

    Route::prefix('booking')->group(function () {
        // Route::get('/', [RESTV1\Manage\Position\Get::class, 'index']);
        // Route::get('{id}', [RESTV1\Manage\Position\Get::class, 'index']);
        Route::post('/', [RESTV1\Transactions\Booking\Insert::class, 'index']);
        // Route::put('{id}', [RESTV1\Manage\Position\Update::class, 'index']);
        // Route::delete('{id}', [RESTV1\Manage\Position\Delete::class, 'index']);
    });
});
