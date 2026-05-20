<?php $__env->startSection('title', 'Laporan Stok'); ?>
<?php $__env->startSection('page-title', 'Laporan Stok'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-800 mb-1">Grafik Stok Masuk & Keluar (7 Hari)</h2>
        <p class="text-sm text-gray-500 mb-5">Perbandingan total qty stok masuk vs stok keluar per hari.</p>
        <canvas id="chartStok" height="100"></canvas>
    </section>

    
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-800 mb-1">Laporan Stok Semua Produk</h2>
        <p class="text-sm text-gray-500 mb-5">Status stok saat ini untuk seluruh produk terdaftar.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">nama_produk</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">ID Produk</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Stok Saat Ini</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800"><?php echo e($product->{'nama_produk'}); ?></td>
                        <td class="px-4 py-3 text-gray-500"><?php echo e($product->id_produk); ?></td>
                        <td class="px-4 py-3 text-right font-semibold
                            <?php echo e($product->jumlah_stok < 5 ? 'text-red-600' : ($product->jumlah_stok < 15 ? 'text-amber-600' : 'text-green-600')); ?>">
                            <?php echo e(number_format($product->jumlah_stok, 0, ',', '.')); ?>

                        </td>
                        <td class="px-4 py-3">
                            <?php if($product->jumlah_stok < 1): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <i class="fa-solid fa-circle-xmark text-xs"></i> Habis
                                </span>
                            <?php elseif($product->jumlah_stok < 5): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                    <i class="fa-solid fa-triangle-exclamation text-xs"></i> Kritis
                                </span>
                            <?php elseif($product->jumlah_stok < 15): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                    <i class="fa-solid fa-circle-minus text-xs"></i> Menipis
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <i class="fa-solid fa-circle-check text-xs"></i> Aman
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-gray-400">
                            <i class="fa-solid fa-box-open text-3xl mb-2 block"></i>
                            Belum ada produk.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>

<script>
const ctx = document.getElementById('chartStok').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($stokLabels, 15, 512) ?>,
        datasets: [
            {
                label: 'Stok Masuk (qty)',
                data: <?php echo json_encode($stokMasuk, 15, 512) ?>,
                borderColor: 'rgba(34,197,94,1)',
                backgroundColor: 'rgba(34,197,94,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
            },
            {
                label: 'Stok Keluar (qty)',
                data: <?php echo json_encode($stokKeluar, 15, 512) ?>,
                borderColor: 'rgba(239,68,68,1)',
                backgroundColor: 'rgba(239,68,68,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sistem_stok_toko\resources\views/laporan/stok.blade.php ENDPATH**/ ?>