<?php

namespace App\Providers;

use App\Events\QuestionAnswered;
use App\Events\RefundApproved;
use App\Events\ReviewCreated;
use App\Listeners\RatingRemoved;
use App\Listeners\SendQuestionAnsweredNotification;
use App\Listeners\SendReviewNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\OrderCreated;
use App\Events\OrderReceived;
use App\Listeners\ManageProductInventory;
use App\Listeners\SendOrderCreationNotification;
use App\Listeners\SendOrderReceivedNotification;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreated::class => [
            SendOrderCreationNotification::class,
            ManageProductInventory::class,
        ],
        OrderReceived::class => [
            SendOrderReceivedNotification::class
        ],
        QuestionAnswered::class => [
            SendQuestionAnsweredNotification::class
        ],
        ReviewCreated::class => [
            SendReviewNotification::class
        ],
        RefundApproved::class => [
            RatingRemoved::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
