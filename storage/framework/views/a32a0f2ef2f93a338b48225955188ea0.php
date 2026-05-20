<?php $__env->startSection('title', 'Upload Resi'); ?>
<?php $__env->startSection('page-title', 'Upload Resi PDF'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-800 mb-5">Upload File Resi PDF</h2>
        <p class="text-sm text-gray-500 mb-5">
            Sistem akan otomatis membaca isi PDF dan mendeteksi produk beserta jumlahnya. Stok akan dikurangi secara otomatis.
        </p>

        <?php if($errors->any()): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside space-y-1"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('shipping-labels.store')); ?>" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File PDF Resi <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition">
                    <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-3xl mb-2 block"></i>
                    <p class="text-sm text-gray-500 mb-2">Pilih atau drag file PDF resi</p>
                    <input type="file" name="pdf_file" accept="application/pdf" required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-400 mt-2">Maks. 10MB</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl transition">
                    <i class="fa-solid fa-upload mr-1"></i> Upload & Proses
                </button>
                <a href="<?php echo e(route('shipping-labels.index')); ?>" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sistem_stok_toko\resources\views/shipping-labels/create.blade.php ENDPATH**/ ?>