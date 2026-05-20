<?php $__env->startSection('title', 'Tambah Produk'); ?>
<?php $__env->startSection('page-title', 'Tambah Produk'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-800 mb-5">Form Tambah Produk</h2>

        <?php if($errors->any()): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('products.store')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="id_produk" value="<?php echo e(old('id_produk')); ?>" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_produk" value="<?php echo e(old('nama_produk')); ?>" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Offline (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_offline" value="<?php echo e(old('harga_offline', 0)); ?>" min="0" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Online (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_online" value="<?php echo e(old('harga_online', 0)); ?>" min="0" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_stok" value="<?php echo e(old('jumlah_stok', 0)); ?>" min="0" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center gap-3 pt-6">
                    <input type="checkbox" name="status" id="status" value="1"
                           <?php echo e(old('status', '1') ? 'checked' : ''); ?>

                           class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="status" class="text-sm font-medium text-gray-700">Produk Aktif</label>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan
                </button>
                <a href="<?php echo e(route('products.index')); ?>" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sistem_stok_toko\resources\views/products/create.blade.php ENDPATH**/ ?>