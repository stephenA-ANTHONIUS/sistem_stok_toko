<?php

namespace App\Providers;

use App\Models\OfflineTransactionItem;
use App\Models\StockEntryItem;
use App\Observers\OfflineTransactionItemObserver;
use App\Observers\StockEntryItemObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (!str_contains(request()->getBaseUrl(), 'localhost') && !$this->app->bound('view')) {
            $this->app->register(\Illuminate\View\ViewServiceProvider::class);
        }
    }

    public function boot(): void
    {
        Paginator::useTailwind();

        OfflineTransactionItem::observe(OfflineTransactionItemObserver::class);
        
        if (config('app.env') !== 'local') {
            config(['view.compiled' => '/tmp/storage/framework/views']);
        }
    }
}