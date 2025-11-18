<?php ob_start(); ?>
<section class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-slate-900 p-6 rounded-xl">
            <h2 class="text-xl font-semibold mb-4">Registro corporal</h2>
            <form method="POST" class="grid grid-cols-2 gap-4">
                <input type="date" name="date" class="bg-slate-800 border border-slate-700 rounded col-span-2">
                <input type="number" step="0.1" name="weight" placeholder="Peso" class="bg-slate-800 border border-slate-700 rounded">
                <input type="number" step="0.1" name="body_fat" placeholder="% Grasa" class="bg-slate-800 border border-slate-700 rounded">
                <textarea name="notes" class="col-span-2 bg-slate-800 border border-slate-700 rounded" placeholder="Notas"></textarea>
                <button class="col-span-2 py-2 bg-indigo-600 rounded">Guardar</button>
            </form>
        </div>
        <div class="bg-slate-900 p-6 rounded-xl">
            <h2 class="text-xl font-semibold mb-4">Historial</h2>
            <div class="space-y-2 max-h-72 overflow-y-auto">
                <?php foreach ($entries as $entry): ?>
                    <div class="flex justify-between text-sm border-b border-slate-800 pb-2">
                        <span><?php echo $entry->date; ?></span>
                        <span><?php echo $entry->weight; ?> kg</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="bg-slate-900 p-6 rounded-xl">
        <h2 class="text-xl font-semibold mb-4">Calendario tipo GitHub</h2>
        <div class="grid grid-cols-12 gap-1">
            <?php foreach ($matrix as $date => $value): ?>
                <div class="w-6 h-6 rounded <?php echo $value ? 'bg-emerald-500/70' : 'bg-slate-800'; ?>" title="<?php echo $date; ?>: <?php echo $value; ?> ejercicios"></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php $slot = ob_get_clean(); include resource_path('views/layouts/app.blade.php'); ?>
