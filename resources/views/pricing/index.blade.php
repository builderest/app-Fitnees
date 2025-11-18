<?php ob_start(); ?>
<section class="bg-slate-900 p-6 rounded-xl space-y-6">
    <h1 class="text-3xl font-bold">Planes</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-6 rounded-2xl border border-slate-800 bg-slate-900">
            <h2 class="text-xl font-semibold">FREE</h2>
            <ul class="text-sm text-slate-400 mt-4 space-y-2">
                <li>Historial 6 meses</li>
                <li>Generador básico</li>
                <li>Rutinas limitadas</li>
            </ul>
        </div>
        <div class="p-6 rounded-2xl border border-indigo-500 bg-slate-800/40">
            <h2 class="text-xl font-semibold">PREMIUM</h2>
            <ul class="text-sm text-slate-200 mt-4 space-y-2">
                <li>Acceso ilimitado</li>
                <li>Generador avanzado</li>
                <li>Stats y PR tracker</li>
            </ul>
        </div>
    </div>
</section>
<?php $slot = ob_get_clean(); include resource_path('views/layouts/app.blade.php'); ?>
