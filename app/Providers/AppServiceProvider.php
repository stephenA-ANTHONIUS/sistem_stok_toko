public function register(): void
{
    // Hapus semua isi di sini, tidak perlu register ViewServiceProvider manual
}

public function boot(): void
{
    Paginator::useTailwind();
    OfflineTransactionItem::observe(OfflineTransactionItemObserver::class);
    StockEntryItem::observe(StockEntryItemObserver::class);
    
    config(['view.compiled' => '/tmp/storage/framework/views']);
}