<?php $__env->startSection('title', 'Tambah Transaksi Offline'); ?>
<?php $__env->startSection('page-title', 'Tambah Transaksi Offline'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl" x-data="transactionForm()">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
        <h2 class="text-base font-bold text-gray-800">Form Tambah Transaksi Offline</h2>

        <?php if(session('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-circle-xmark"></i> <?php echo e(session('error')); ?>

        </div>
        <?php endif; ?>
        
        

        <form method="POST" action="<?php echo e(route('offline-transactions.store')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi <span class="text-red-500">*</span></label>
                    
                    <input type="date" name="transaction_date" value="<?php echo e(date('Y-m-d')); ?>" readonly required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan <span class="text-red-500">*</span></label>
                    
                    <input type="text" name="notes" value="<?php echo e(old('notes')); ?>" required
                           class="w-full px-4 py-2.5 border <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 focus:ring-red-500 <?php else: ?> border-gray-300 focus:ring-blue-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl text-sm focus:outline-none focus:ring-2"
                           placeholder="Wajib Isi nama Pembeli">
                    
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-1 font-medium"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Daftar Produk</h3>
                        <p class="text-xs text-gray-500">Cari produk untuk menambahkan ke transaksi.</p>
                    </div>
                    <div class="flex gap-2 items-center w-full sm:w-auto">
                        <input type="text" x-model="searchTerm" placeholder="Cari produk..."
                               class="w-full sm:w-72 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="button" @click="addItem"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                            <i class="fa-solid fa-plus"></i> Tambah
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3">
                            <div class="flex-1">
                                <select :name="'items[' + index + '][product_id]'" x-model="item.product_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                    <option value="">-- Pilih Produk --</option>
                                    <template x-for="product in filteredProducts" :key="product.id">
                                        <option :value="product.id" x-text="product.label"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="w-28">
                                <input type="number" :name="'items[' + index + '][qty]'" x-model="item.qty"
                                       min="1" required placeholder="Qty"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="button" @click="removeItem(index)" x-show="items.length > 1 || item.product_id !== ''"
                                    class="text-red-400 hover:text-red-600 transition p-1">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Transaksi
                </button>
                <a href="<?php echo e(route('offline-transactions.index')); ?>" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php
    $productOptions = $products->map(function ($product) {
        return [
            'id' => $product->id,
            'label' => $product->nama_produk . ' (Stok: ' . $product->jumlah_stok . ')',
            'nama_produk' => $product->nama_produk,
            'jumlah_stok' => $product->jumlah_stok,
        ];
    })->values();
?>

<script>
function transactionForm() {
    return {
        searchTerm: '',
        items: [{ product_id: '', qty: 1 }],
        products: <?php echo json_encode($productOptions, 15, 512) ?>,
        get filteredProducts() {
            if (!this.searchTerm) {
                return this.products;
            }
            return this.products.filter(product => product.label.toLowerCase().includes(this.searchTerm.toLowerCase()));
        },
        addItem() { this.items.push({ product_id: '', qty: 1 }); },
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            } else {
                this.items = [{ product_id: '', qty: 1 }];
            }
        }
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project_KP\sistem_stok_toko\resources\views/offline-transactions/create.blade.php ENDPATH**/ ?>