<?php ob_start(); ?>
<section class="bg-slate-900 p-6 rounded-xl space-y-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="text-xs text-slate-400">Grupo muscular</label>
            <select name="muscle" class="w-full bg-slate-800 border border-slate-700 rounded">
                <option value="">Todos</option>
                <?php foreach (['pecho','espalda','piernas','gluteos','hombros','brazos','core'] as $option): ?>
                    <option value="<?php echo $option; ?>" <?php echo ($filters['muscle'] ?? '') === $option ? 'selected' : ''; ?>><?php echo ucfirst($option); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="text-xs text-slate-400">Equipo</label>
            <select name="equipment" class="w-full bg-slate-800 border border-slate-700 rounded">
                <option value="">Todos</option>
                <?php foreach (['bodyweight','dumbbells','barbell','machines','bands'] as $option): ?>
                    <option value="<?php echo $option; ?>" <?php echo ($filters['equipment'] ?? '') === $option ? 'selected' : ''; ?>><?php echo ucfirst($option); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="text-xs text-slate-400">Dificultad</label>
            <select name="difficulty" class="w-full bg-slate-800 border border-slate-700 rounded">
                <option value="">Todas</option>
                <?php foreach (['easy','medium','hard'] as $option): ?>
                    <option value="<?php echo $option; ?>" <?php echo ($filters['difficulty'] ?? '') === $option ? 'selected' : ''; ?>><?php echo ucfirst($option); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="text-xs text-slate-400">Búsqueda</label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>" class="w-full bg-slate-800 border border-slate-700 rounded">
        </div>
    </form>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php foreach ($exercises as $exercise): ?>
            <article class="bg-slate-800/60 rounded-xl p-4 space-y-2">
                <img src="<?php echo $exercise->thumbnail_url; ?>" class="rounded-lg w-full h-40 object-cover" alt="<?php echo $exercise->name; ?>">
                <h3 class="text-lg font-semibold"><?php echo $exercise->name; ?></h3>
                <p class="text-sm text-slate-400"><?php echo $exercise->primary_muscle; ?> · <?php echo $exercise->equipment; ?></p>
                <a href="/exercise?slug=<?php echo $exercise->slug; ?>" class="text-indigo-400 text-sm">Ver detalle</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php $slot = ob_get_clean(); include resource_path('views/layouts/app.blade.php'); ?>
