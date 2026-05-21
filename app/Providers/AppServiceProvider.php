<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\OfflineTransactionItem;
use App\Models\StockEntryItem;
use App\Observers\OfflineTransactionItemObserver;
use App\Observers\StockEntryItemObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useTailwind();
        OfflineTransactionItem::observe(OfflineTransactionItemObserver::class);
        StockEntryItem::observe(StockEntryItem::class);
        config(['view.compiled' => '/tmp/storage/framework/views']);
    }
}