<?php $__env->startSection('title', 'Transaksi Online'); ?>
<?php $__env->startSection('page-title', 'Transaksi Online (Resi)'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Daftar Resi / Transaksi Online</h2>
            <p class="text-sm text-gray-500">Upload dan kelola resi PDF transaksi online.</p>
        </div>
        <a href="<?php echo e(route('shipping-labels.create')); ?>"
           class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
            <i class="fa-solid fa-upload"></i> Upload Resi
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">File PDF</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal Upload</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Produk Terdeteksi</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Preview Isi</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $shippingLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-file-pdf text-red-500 text-lg"></i>
                                <span class="text-gray-600 text-xs"><?php echo e(basename($label->image_path)); ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e($label->created_at->format('d/m/Y H:i')); ?></td>
                        <td class="px-4 py-3 text-gray-700 text-xs max-w-xs">
                            <?php if($label->items): ?>
                                <?php $__currentLoopData = array_slice($label->items, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 mr-1 mb-1">
                                        <?php echo e($item['produk'] ?? ''); ?> (<?php echo e($item['qty'] ?? 0); ?>)
                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if(count($label->items) > 3): ?>
                                    <span class="text-gray-400">+<?php echo e(count($label->items) - 3); ?> lainnya</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="px-4 py-3 text-gray-500 text-xs max-w-xs truncate"><?php echo e(\Illuminate\Support\Str::limit($label->raw_text, 60)); ?></td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('shipping-labels.edit', $label)); ?>"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form method="POST" action="<?php echo e(route('shipping-labels.destroy', $label)); ?>"
                                      onsubmit="return confirm('Yakin ingin menghapus resi ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs hover:bg-red-100 transition">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                            <i class="fa-solid fa-file-pdf text-3xl mb-2 block"></i>
                            Belum ada resi diupload.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($shippingLabels->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-100"><?php echo e($shippingLabels->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project_KP\sistem_stok_toko\resources\views/shipping-labels/index.blade.php ENDPATH**/ ?>