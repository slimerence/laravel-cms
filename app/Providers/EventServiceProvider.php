<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        // 开始加载html head
        'App\Events\Page\Head\StartLoading' => [
            'App\Listeners\Page\Head\StartLoadingEventListener',
        ],
        'App\Events\Page\Head\FinishLoading' => [
            'App\Listeners\Page\Head\FinishLoadingEventListener',
        ],
        // 开始加载页面的Header 部分
        'App\Events\Page\Header\StartLoading' => [
            'App\Listeners\Page\Header\StartLoadingEventListener',
        ],
        'App\Events\Page\Header\FinishLoading' => [
            'App\Listeners\Page\Header\FinishLoadingEventListener',
        ],
        // 开始加载页面的 Content 部分
        'App\Events\Page\Content\StartLoading' => [
            'App\Listeners\Page\Content\StartLoadingEventListener',
        ],
        'App\Events\Page\Content\FinishLoading' => [
            'App\Listeners\Page\Content\FinishLoadingEventListener',
        ],
        // 开始加载页面的 Footer 部分
        'App\Events\Page\Footer\StartLoading' => [
            'App\Listeners\Page\Footer\StartLoadingEventListener',
        ],
        'App\Events\Page\Footer\FinishLoading' => [
            'App\Listeners\Page\Footer\FinishLoadingEventListener',
        ],
        // 开始加载页面的 JS 部分
        'App\Events\Page\JS\StartLoading' => [
            'App\Listeners\Page\JS\StartLoadingEventListener',
        ],
        'App\Events\Page\JS\FinishLoading' => [
            'App\Listeners\Page\JS\FinishLoadingEventListener',
        ],
        // 开始加载页面的 Hook 部分
        'App\Events\Page\Hook\StartLoading' => [
            'App\Listeners\Page\Hook\StartLoadingEventListener',
        ],
        'App\Events\Page\Hook\FinishLoading' => [
            'App\Listeners\Page\Hook\FinishLoadingEventListener',
        ],
	// Leads Received
        'App\Events\Contact\LeadReceived' => [
            'App\Listeners\Contact\LeadReceivedEventListener',
        ],
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
