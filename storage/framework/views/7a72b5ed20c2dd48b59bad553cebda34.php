<?php $__env->startSection('title', 'Tambah Stok Masuk'); ?>
<?php $__env->startSection('page-title', 'Tambah Stok Masuk'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl" x-data="stockForm()">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
        <h2 class="text-base font-bold text-gray-800">Form Tambah Stok Masuk</h2>

        <?php if(session('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-circle-xmark"></i> <?php echo e(session('error')); ?>

        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('stock-entries.store')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk <span class="text-red-500">*</span></label>
                    <input type="date" name="entry_date" value="<?php echo e(old('entry_date', date('Y-m-d'))); ?>" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <input type="text" name="notes" value="<?php echo e(old('notes')); ?>"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Opsional...">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">Daftar Produk</h3>
                    <button type="button" @click="addItem"
                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                        <i class="fa-solid fa-plus"></i> Tambah Produk
                    </button>
                </div>
                <div class="space-y-2">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3">
                            <div class="flex-1">
                                <select :name="'items[' + index + '][product_id]'" x-model="item.product_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                    <option value="">-- Pilih Produk --</option>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->id); ?>"><?php echo e($p->{'nama_produk'}); ?> (Stok: <?php echo e($p->jumlah_stok); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="w-28">
                                <input type="number" :name="'items[' + index + '][qty]'" x-model="item.qty"
                                       min="1" required placeholder="Qty"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                    class="text-red-400 hover:text-red-600 transition p-1">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan
                </button>
                <a href="<?php echo e(route('stock-entries.index')); ?>" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<script>
function stockForm() {
    return {
        items: [{ product_id: '', qty: 1 }],
        addItem() { this.items.push({ product_id: '', qty: 1 }); },
        removeItem(index) { if (this.items.length > 1) this.items.splice(index, 1); }
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sistem_stok_toko\resources\views/stock-entries/create.blade.php ENDPATH**/ ?>