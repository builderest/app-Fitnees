<?php ob_start(); ?>
<section class="space-y-6">
    <h1 class="text-2xl font-semibold">Panel administrativo</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-slate-900 p-5 rounded-xl">
            <p class="text-sm text-slate-400">Usuarios</p>
            <h2 class="text-3xl font-bold"><?php echo count($users); ?></h2>
        </div>
        <div class="bg-slate-900 p-5 rounded-xl">
            <p class="text-sm text-slate-400">Ejercicios</p>
            <h2 class="text-3xl font-bold"><?php echo count($exercises); ?></h2>
        </div>
        <div class="bg-slate-900 p-5 rounded-xl">
            <p class="text-sm text-slate-400">Programas</p>
            <h2 class="text-3xl font-bold"><?php echo count($programs); ?></h2>
        </div>
    </div>
    <div class="bg-slate-900 p-5 rounded-xl">
        <h2 class="text-xl font-semibold mb-4">Nuevo ejercicio</h2>
        <form method="POST" action="/admin/exercises" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input name="name" placeholder="Nombre" class="bg-slate-800 border border-slate-700 rounded">
            <input name="name_en" placeholder="Nombre EN" class="bg-slate-800 border border-slate-700 rounded">
            <input name="description" placeholder="Descripción" class="col-span-2 bg-slate-800 border border-slate-700 rounded">
            <input name="primary_muscle" placeholder="Músculo principal" class="bg-slate-800 border border-slate-700 rounded">
            <input name="equipment" placeholder="Equipo" class="bg-slate-800 border border-slate-700 rounded">
            <input name="difficulty" placeholder="Dificultad" class="bg-slate-800 border border-slate-700 rounded">
            <input name="video_url" placeholder="Video URL" class="col-span-2 bg-slate-800 border border-slate-700 rounded">
            <input name="thumbnail_url" placeholder="Thumbnail" class="col-span-2 bg-slate-800 border border-slate-700 rounded">
            <button class="col-span-2 py-2 bg-indigo-600 rounded">Guardar</button>
        </form>
    </div>
</section>
<?php $slot = ob_get_clean(); include resource_path('views/layouts/app.blade.php'); ?>
