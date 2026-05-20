<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Sistem Stok Toko'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link.active { background-color: rgba(255,255,255,0.15); }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
<div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white flex flex-col transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 lg:flex">

        <div class="flex items-center gap-3 px-6 py-5 border-b border-blue-700">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-store text-blue-800 text-base"></i>
            </div>
            <div>
                <p class="font-bold text-sm leading-tight">Sistem Stok</p>
                <p class="text-blue-300 text-xs">Manajemen Toko</p>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-sm">

            
            <p class="px-3 py-1 text-blue-400 text-xs font-semibold uppercase tracking-wider">Dashboard</p>
            <a href="<?php echo e(route('dashboard')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition <?php echo e(request()->routeIs('dashboard') ? 'bg-white/15 font-semibold' : ''); ?>">
                <i class="fa-solid fa-house w-4 text-center"></i> Dashboard
            </a>

            
            <?php if(auth()->user()->role === 'admin'): ?>
            <p class="px-3 pt-3 pb-1 text-blue-400 text-xs font-semibold uppercase tracking-wider">Master Data</p>
            <a href="<?php echo e(route('products.index')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition <?php echo e(request()->routeIs('products.*') ? 'bg-white/15 font-semibold' : ''); ?>">
                <i class="fa-solid fa-box w-4 text-center"></i> Produk
            </a>
            <a href="<?php echo e(route('stock-entries.index')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition <?php echo e(request()->routeIs('stock-entries.*') ? 'bg-white/15 font-semibold' : ''); ?>">
                <i class="fa-solid fa-arrow-up w-4 text-center"></i> Stok Masuk
            </a>

            <p class="px-3 pt-3 pb-1 text-blue-400 text-xs font-semibold uppercase tracking-wider">Transaksi</p>
            <a href="<?php echo e(route('offline-transactions.index')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition <?php echo e(request()->routeIs('offline-transactions.*') ? 'bg-white/15 font-semibold' : ''); ?>">
                <i class="fa-solid fa-receipt w-4 text-center"></i> Transaksi Offline
            </a>
            <a href="<?php echo e(route('shipping-labels.index')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition <?php echo e(request()->routeIs('shipping-labels.*') ? 'bg-white/15 font-semibold' : ''); ?>">
                <i class="fa-solid fa-globe w-4 text-center"></i> Transaksi Online
            </a>
            <?php endif; ?>

            
            <p class="px-3 pt-3 pb-1 text-blue-400 text-xs font-semibold uppercase tracking-wider">Laporan</p>
            
            
            <a href="<?php echo e(route('laporan.penjualan')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition <?php echo e(request()->routeIs('laporan.penjualan') ? 'bg-white/15 font-semibold' : ''); ?>">
                <i class="fa-solid fa-chart-bar w-4 text-center"></i> Laporan Penjualan
            </a>
            <a href="<?php echo e(route('laporan.stok')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition <?php echo e(request()->routeIs('laporan.stok') ? 'bg-white/15 font-semibold' : ''); ?>">
                <i class="fa-solid fa-chart-line w-4 text-center"></i> Laporan Stok
            </a>
            
            
            <?php if(auth()->user()->role === 'admin'): ?>
            <a href="<?php echo e(route('laporan.riwayat')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition <?php echo e(request()->routeIs('laporan.riwayat') ? 'bg-white/15 font-semibold' : ''); ?>">
                <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i> Riwayat Transaksi
            </a>
            <?php endif; ?>

            
            <?php if(auth()->user()->role === 'pemilik'): ?>
            <p class="px-3 pt-3 pb-1 text-blue-400 text-xs font-semibold uppercase tracking-wider">Pengaturan</p>
            <a href="<?php echo e(route('users.index')); ?>"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 transition <?php echo e(request()->routeIs('users.*') ? 'bg-white/15 font-semibold' : ''); ?>">
                <i class="fa-solid fa-users w-4 text-center"></i> Manajemen User
            </a>
            <?php endif; ?>
        </nav>

        <div class="px-4 py-4 border-t border-blue-700">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-xs font-bold">
                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold truncate"><?php echo e(auth()->user()->name); ?></p>
                    <p class="text-blue-300 text-xs capitalize"><?php echo e(auth()->user()->role); ?></p>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs rounded-lg bg-blue-700 hover:bg-blue-600 transition">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
         class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

    
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <header class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between shadow-sm">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h1 class="text-base font-semibold text-gray-700 lg:ml-0 ml-4"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <i class="fa-solid fa-calendar-days"></i>
                <span><?php echo e(now()->translatedFormat('d F Y')); ?></span>
            </div>
        </header>

        
        <main class="flex-1 overflow-y-auto p-6">

            <?php if(session('success')): ?>
                <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
                    <i class="fa-solid fa-circle-xmark text-red-500"></i>
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>
</body>
</html><?php /**PATH D:\Project_KP\sistem_stok_toko\resources\views/layouts/app.blade.php ENDPATH**/ ?>