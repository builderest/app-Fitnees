<?php ob_start(); ?>
<section class="bg-slate-900 p-6 rounded-xl space-y-5">
    <div class="flex flex-col md:flex-row gap-6">
        <div class="md:w-2/3">
            <div class="aspect-video bg-black rounded-xl overflow-hidden">
                <iframe src="<?php echo $exercise->video_url; ?>" class="w-full h-full" allowfullscreen></iframe>
            </div>
        </div>
        <div class="md:w-1/3 space-y-3">
            <h1 class="text-2xl font-bold"><?php echo $exercise->name; ?></h1>
            <p class="text-sm text-slate-400"><?php echo $exercise->description; ?></p>
            <p class="text-sm text-slate-400">Músculos secundarios: <?php echo implode(', ', $exercise->secondary_muscles); ?></p>
            <button class="px-4 py-2 rounded bg-indigo-600">Agregar a rutina</button>
        </div>
    </div>
</section>
<?php $slot = ob_get_clean(); include resource_path('views/layouts/app.blade.php'); ?>
