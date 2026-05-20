<?php $__env->startSection('title', 'Stok Masuk'); ?>
<?php $__env->startSection('page-title', 'Stok Masuk'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Daftar Stok Masuk</h2>
            <p class="text-sm text-gray-500">Riwayat penambahan stok produk.</p>
        </div>
        <a href="<?php echo e(route('stock-entries.create')); ?>"
           class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
            <i class="fa-solid fa-plus"></i> Tambah Stok
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal Masuk</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Total Qty</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Produk</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Catatan</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $stockEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-800"><?php echo e(\Carbon\Carbon::parse($entry->entry_date)->format('d/m/Y')); ?></td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800"><?php echo e(number_format($entry->items->sum('qty'), 0, ',', '.')); ?></td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs">
                            <?php echo e($entry->items->map(fn($i) => ($i->product->{'nama_produk'} ?? '?').' ('.$i->qty.')')->join(', ')); ?>

                        </td>
                        <td class="px-4 py-3 text-gray-500"><?php echo e($entry->notes ?? '-'); ?></td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('stock-entries.edit', $entry)); ?>"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form method="POST" action="<?php echo e(route('stock-entries.destroy', $entry)); ?>"
                                      onsubmit="return confirm('Yakin ingin menghapus data stok masuk ini?')">
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
                            <i class="fa-solid fa-arrow-up text-3xl mb-2 block"></i>
                            Belum ada data stok masuk.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($stockEntries->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-100"><?php echo e($stockEntries->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sistem_stok_toko\resources\views/stock-entries/index.blade.php ENDPATH**/ ?>