<?php ob_start(); ?>
<section class="space-y-6">
    <div class="bg-slate-900 p-6 rounded-xl">
        <h2 class="text-xl font-semibold mb-4">Sesiones recientes</h2>
        <div class="space-y-3">
            <?php foreach ($sessions as $session): ?>
                <div class="flex items-center justify-between border border-slate-800 rounded-lg p-4">
                    <div>
                        <p class="text-sm text-slate-400"><?php echo $session->date; ?></p>
                        <p class="text-lg font-semibold"><?php echo $session->status; ?></p>
                    </div>
                    <div class="text-right text-sm text-slate-400"><?php echo $session->completed_exercises; ?>/<?php echo $session->total_exercises; ?> ejercicios</div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php $slot = ob_get_clean(); include resource_path('views/layouts/app.blade.php'); ?>
