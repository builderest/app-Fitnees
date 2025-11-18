<?php ob_start(); ?>
<section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <form method="POST" class="bg-slate-900 rounded-xl p-6 space-y-4">
        <h2 class="text-xl font-semibold">Generador inteligente</h2>
        <div>
            <p class="text-sm text-slate-400 mb-2">Equipamiento disponible</p>
            <?php foreach (['bodyweight','dumbbells','barbell','machines','bands'] as $equip): ?>
                <label class="flex items-center space-x-2 text-sm">
                    <input type="checkbox" name="equipment[]" value="<?php echo $equip; ?>" class="text-indigo-600">
                    <span><?php echo ucfirst($equip); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <div>
            <p class="text-sm text-slate-400 mb-2">Grupos musculares</p>
            <?php foreach (['pecho','espalda','piernas','gluteos','hombros','brazos','core'] as $muscle): ?>
                <label class="flex items-center space-x-2 text-sm">
                    <input type="checkbox" name="muscles[]" value="<?php echo $muscle; ?>">
                    <span><?php echo ucfirst($muscle); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <div>
            <label class="text-sm text-slate-400">Cantidad de ejercicios</label>
            <input type="number" name="count" value="6" class="w-full bg-slate-800 border border-slate-700 rounded">
        </div>
        <button class="w-full py-2 bg-indigo-600 rounded">Generar</button>
    </form>
    <div class="lg:col-span-2 bg-slate-900 rounded-xl p-6 space-y-4">
        <h2 class="text-xl font-semibold">Resultado</h2>
        <?php if (isset($result)): ?>
            <div class="space-y-4">
                <?php foreach ($result->exercises as $index => $item): ?>
                    <div class="p-4 border border-slate-800 rounded-lg flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-400">Paso <?php echo $index + 1; ?></p>
                            <h3 class="text-lg font-semibold"><?php echo $item['exercise']->name; ?></h3>
                        </div>
                        <div class="text-right text-sm text-slate-400"><?php echo $item['sets']; ?> x <?php echo $item['reps']; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-slate-500">Configura el generador para ver propuestas.</p>
        <?php endif; ?>
    </div>
</section>
<?php $slot = ob_get_clean(); include resource_path('views/layouts/app.blade.php'); ?>
