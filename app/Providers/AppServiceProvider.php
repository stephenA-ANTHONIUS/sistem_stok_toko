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
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useTailwind();

        OfflineTransactionItem::observe(OfflineTransactionItemObserver::class);
    }
}
