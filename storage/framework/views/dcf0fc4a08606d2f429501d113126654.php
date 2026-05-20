<?php $__env->startSection('title', 'Laporan Penjualan'); ?>
<?php $__env->startSection('page-title', 'Laporan Penjualan'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-800 mb-1">Ringkasan Omset Penjualan</h2>
        <p class="text-sm text-gray-500 mb-5">Total keseluruhan omset offline, online, dan gabungan.</p>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <?php
                // FUNGSI SUDAH DIPERBAIKI: Mengubah format penyingkat K/J menjadi nominal penuh '000'
                function fmtRp($n) {
                    $n = (int)$n;
                    return 'Rp ' . number_format($n, 0, ',', '.');
                }
            ?>
            
            <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-store text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Omset Offline</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(fmtRp($offlineOmset)); ?></p>
                <p class="text-xs text-gray-400 mt-1">Total transaksi langsung</p>
            </div>
            
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-globe text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Omset Online</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(fmtRp($onlineOmset)); ?></p>
                <p class="text-xs text-gray-400 mt-1">Perkiraan harga online</p>
            </div>
            
            <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-chart-pie text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Total Omset</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(fmtRp($offlineOmset + $onlineOmset)); ?></p>
                <p class="text-xs text-gray-400 mt-1">Gabungan seluruh omset</p>
            </div>
            
            <div class="bg-purple-50 border border-purple-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-boxes-stacked text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Qty Online Terjual</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(number_format($onlineQty, 0, ',', '.')); ?></p>
                <p class="text-xs text-gray-400 mt-1">Total pcs dari resi</p>
            </div>
        </div>
    </section>

    
    <section class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-800 mb-1">Grafik Transaksi 7 Hari Terakhir</h2>
        <p class="text-sm text-gray-500 mb-5">Jumlah transaksi offline dan qty produk online per hari.</p>
        <canvas id="chartPenjualan" height="100"></canvas>
    </section>

</div>

<script>
const ctx = document.getElementById('chartPenjualan').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels, 15, 512) ?>,
        datasets: [
            {
                label: 'Transaksi Offline (jumlah)',
                data: <?php echo json_encode($offlineCounts, 15, 512) ?>,
                backgroundColor: 'rgba(34,197,94,0.7)',
                borderColor: 'rgba(34,197,94,1)',
                borderWidth: 1,
                borderRadius: 6,
            },
            {
                label: 'Produk Online Terjual (qty)',
                data: <?php echo json_encode($onlineQtys, 15, 512) ?>,
                backgroundColor: 'rgba(59,130,246,0.7)',
                borderColor: 'rgba(59,130,246,1)',
                borderWidth: 1,
                borderRadius: 6,
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sistem_stok_toko\resources\views/laporan/penjualan.blade.php ENDPATH**/ ?>