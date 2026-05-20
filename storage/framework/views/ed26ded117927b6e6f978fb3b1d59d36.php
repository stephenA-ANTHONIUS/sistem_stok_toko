<?php $__env->startSection('title', 'Manajemen User'); ?>
<?php $__env->startSection('page-title', 'Manajemen User'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Daftar User</h2>
            <p class="text-sm text-gray-500">Kelola akun pengguna sistem.</p>
        </div>
        <a href="<?php echo e(route('users.create')); ?>"
           class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
            <i class="fa-solid fa-plus"></i> Tambah User
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Role</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Dibuat</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs">
                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                </div>
                                <span class="font-medium text-gray-800"><?php echo e($user->name); ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e($user->email); ?></td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php echo e($user->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'); ?>">
                                <?php echo e(ucfirst($user->role)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500"><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('users.edit', $user)); ?>"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <?php if($user->id !== auth()->id()): ?>
                                <form method="POST" action="<?php echo e(route('users.destroy', $user)); ?>"
                                      onsubmit="return confirm('Yakin ingin menghapus user <?php echo e($user->name); ?>?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs hover:bg-red-100 transition">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                            <i class="fa-solid fa-users text-3xl mb-2 block"></i>
                            Belum ada user.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($users->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-100"><?php echo e($users->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project_KP\sistem_stok_toko\resources\views/users/index.blade.php ENDPATH**/ ?>