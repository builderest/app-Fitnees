<?php ob_start(); ?>
<section class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <div class="bg-slate-900 p-5 rounded-xl">
        <p class="text-slate-400 text-sm">Plan</p>
        <h2 class="text-2xl font-semibold"><?php echo htmlspecialchars($user->plan); ?></h2>
        <p class="text-sm text-slate-500">Nivel: <?php echo htmlspecialchars($user->training_level); ?></p>
    </div>
    <div class="bg-slate-900 p-5 rounded-xl">
        <p class="text-slate-400 text-sm">Sesiones totales</p>
        <h2 class="text-2xl font-semibold"><?php echo count($sessions); ?></h2>
    </div>
    <div class="bg-slate-900 p-5 rounded-xl">
        <p class="text-slate-400 text-sm">Meta</p>
        <h2 class="text-2xl font-semibold"><?php echo htmlspecialchars($user->training_goal); ?></h2>
    </div>
</section>
<section class="bg-slate-900 p-5 rounded-xl">
    <h3 class="text-lg font-semibold mb-4">Evolución del peso</h3>
    <canvas id="weightChart" height="140"></canvas>
</section>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('weightChart');
        if (!ctx) return;
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($weightSeries, 'date')); ?>,
                datasets: [{
                    label: 'Peso',
                    borderColor: '#818cf8',
                    backgroundColor: 'rgba(129, 140, 248, 0.2)',
                    data: <?php echo json_encode(array_column($weightSeries, 'weight')); ?>
                }]
            },
            options: { responsive: true }
        });
    });
</script>
<?php $slot = ob_get_clean(); include resource_path('views/layouts/app.blade.php'); ?>
