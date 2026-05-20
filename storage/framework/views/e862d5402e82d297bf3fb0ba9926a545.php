<?php $__env->startSection('title', 'Edit Resi'); ?>
<?php $__env->startSection('page-title', 'Edit Rekap Resi'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl" x-data="editItems(<?php echo e(json_encode($shippingLabel->items ?? [])); ?>, <?php echo e(json_encode($products->toArray())); ?>)">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
        <h2 class="text-base font-bold text-gray-800">Edit Rekap Produk Resi</h2>

        <form method="POST" action="<?php echo e(route('shipping-labels.update', $shippingLabel)); ?>" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">Daftar Produk Terdeteksi</h3>
                    <button type="button" @click="addItem"
                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                        <i class="fa-solid fa-plus"></i> Tambah Baris
                    </button>
                </div>
                <div class="space-y-2">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3">
                            
                            <div class="flex-1">
                                <select :name="'items[' + index + '][produk]'" 
                                        x-model="item.produk" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" disabled>-- Pilih Produk dari Database --</option>
                                    <template x-for="prod in dbProducts" :key="prod.id">
                                        <option :value="prod.nama_produk" x-text="prod.nama_produk"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="w-28">
                                <input type="number" :name="'items[' + index + '][qty]'" x-model="item.qty"
                                       min="1" placeholder="Qty" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                    class="text-red-400 hover:text-red-600 transition p-1">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <details>
                    <summary class="text-sm font-medium text-gray-600 cursor-pointer">Log Teks Mentah PDF</summary>
                    <pre class="mt-3 text-xs text-gray-500 whitespace-pre-wrap max-h-60 overflow-y-auto"><?php echo e($shippingLabel->raw_text); ?></pre>
                </details>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Perbarui
                </button>
                <a href="<?php echo e(route('shipping-labels.index')); ?>" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function editItems(initialItems, dbProducts) {
    return {
        items: initialItems.length ? initialItems.map(i => ({
            produk: i.produk ? i.produk.trim() : '',
            qty: i.qty
        })) : [{ produk: '', qty: 1 }],
        dbProducts: dbProducts,
        addItem() { 
            this.items.push({ produk: '', qty: 1 }); 
        },
        removeItem(index) { 
            if (this.items.length > 1) this.items.splice(index, 1); 
        }
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\sistem_stok_toko\resources\views/shipping-labels/edit.blade.php ENDPATH**/ ?>