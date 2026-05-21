public function register(): void
{
    // kosongkan, tidak perlu isi apapun
}

public function boot(): void
{
    Paginator::useTailwind();
    OfflineTransactionItem::observe(OfflineTransactionItemObserver::class);
    StockEntryItem::observe(StockEntryItemObserver::class);
    
    config(['view.compiled' => '/tmp/storage/framework/views']);
}