<?php ob_start(); ?>
<section class="space-y-4">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Mis rutinas</h1>
        <a href="/routines/create" class="px-4 py-2 bg-indigo-600 rounded">Crear rutina</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php foreach ($programs as $program): ?>
            <article class="bg-slate-900 p-5 rounded-xl border border-slate-800 space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold"><?php echo $program->title; ?></h2>
                    <?php if ($program->is_active): ?><span class="text-xs px-2 py-1 rounded bg-green-500/20 text-green-400">Activa</span><?php endif; ?>
                </div>
                <p class="text-sm text-slate-400">Tipo: <?php echo strtoupper($program->type); ?></p>
                <div class="flex gap-3">
                    <form method="POST" action="/routines/activate">
                        <input type="hidden" name="id" value="<?php echo $program->id; ?>">
                        <button class="px-3 py-1 bg-indigo-600 rounded text-sm">Activar</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php $slot = ob_get_clean(); include resource_path('views/layouts/app.blade.php'); ?>
