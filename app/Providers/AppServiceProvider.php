<?php

namespace App\Providers;

use App\Models\OfflineTransactionItem;
use App\Models\StockEntryItem;
use App\Observers\OfflineTransactionItemObserver;
use App\Observers\StockEntryItemObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
            if ($this->app->environment('production')) {
        URL::forceScheme('https');
    }
        // Gunakan Tailwind-compatible pagination
        Paginator::useTailwind();

        // Daftarkan observer yang sudah ada (tidak diubah)
        OfflineTransactionItem::observe(OfflineTransactionItemObserver::class);
        
    }
}

