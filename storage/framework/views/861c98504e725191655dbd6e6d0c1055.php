<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Sistem Stok Toko</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-800 to-blue-950 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
                <i class="fa-solid fa-store text-blue-800 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Sistem Stok Toko</h1>
            <p class="text-blue-300 text-sm mt-1">Silakan login untuk melanjutkan</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <?php if($errors->any()): ?>
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </span>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="email@contoh.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" required
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-blue-600">
                    <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
                </div>

                <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk
                </button>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\sistem_stok_toko\resources\views/auth/login.blade.php ENDPATH**/ ?>