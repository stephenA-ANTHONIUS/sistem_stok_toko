<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <section class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-1">Omset Penjualan</h2>
        <p class="text-sm text-gray-500 mb-5">Ringkasan omset offline, online, dan total penjualan.</p>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <?php
                // Fungsi format disederhanakan: langsung format ke Rupiah dengan pemisah titik
                function fmtRupiah($n) {
                    return 'Rp ' . number_format((int)$n, 0, ',', '.');
                }
            ?>
            <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-money-bills text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Omset Offline</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(fmtRupiah($offlineOmset)); ?></p>
                <p class="text-xs text-gray-400 mt-1">Total pendapatan offline</p>
            </div>
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-cart-shopping text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Omset Online</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(fmtRupiah($onlineOmset)); ?></p>
                <p class="text-xs text-gray-400 mt-1">Perkiraan pendapatan online</p>
            </div>
            <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-arrow-trend-up text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Total Omset</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(fmtRupiah($offlineOmset + $onlineOmset)); ?></p>
                <p class="text-xs text-gray-400 mt-1">Jumlah keseluruhan</p>
            </div>
            <div class="bg-purple-50 border border-purple-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-shopping-bag text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Produk Online Terjual</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(number_format($onlineQty, 0, ',', '.')); ?></p>
                <p class="text-xs text-gray-400 mt-1">Total qty terjual online</p>
            </div>
        </div>
    </section>

    
    <section class="rounded-2xl bg-white shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-1">Ringkasan Gudang</h2>
        <p class="text-sm text-gray-500 mb-5">Metrik stok dan aktivitas transaksi terbaru.</p>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-cube text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Total Produk</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(number_format($totalProduk, 0, ',', '.')); ?></p>
                <p class="text-xs text-gray-400 mt-1">Item produk terdaftar</p>
            </div>
            <div class="bg-teal-50 border border-teal-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-teal-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Total Stok</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(number_format($totalStok, 0, ',', '.')); ?></p>
                <p class="text-xs text-gray-400 mt-1">Jumlah stok saat ini</p>
            </div>
            <div class="bg-sky-50 border border-sky-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-sky-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-circle-check text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Transaksi Hari Ini</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(number_format($transaksiHariIni, 0, ',', '.')); ?></p>
                <p class="text-xs text-gray-400 mt-1">Total transaksi & pengiriman</p>
            </div>
            <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-triangle-exclamation text-white text-xs"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-medium">Stok Menipis</span>
                </div>
                <p class="text-xl font-bold text-gray-800"><?php echo e(number_format($stokMenipis, 0, ',', '.')); ?></p>
                <p class="text-xs text-gray-400 mt-1">Produk dengan stok &lt; 5 pcs</p>
            </div>
        </div>
    </section>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project_KP\sistem_stok_toko\resources\views/dashboard.blade.php ENDPATH**/ ?>