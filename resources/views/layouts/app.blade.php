<!DOCTYPE html>
<html lang="es" x-data="{ sidebar: false }" class="dark">
<head>
    <meta charset="UTF-8">
    <title>FitForge</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
    <div class="flex">
        <aside class="w-64 bg-slate-900 min-h-screen hidden md:block">
            <div class="p-6 font-bold text-xl">FitForge</div>
            <nav class="px-4 space-y-2">
                <a href="/dashboard" class="block px-3 py-2 rounded hover:bg-slate-800">Dashboard</a>
                <a href="/generator" class="block px-3 py-2 rounded hover:bg-slate-800">Workout Generator</a>
                <a href="/exercises" class="block px-3 py-2 rounded hover:bg-slate-800">Exercises</a>
                <a href="/routines" class="block px-3 py-2 rounded hover:bg-slate-800">My Routines</a>
                <a href="/sessions" class="block px-3 py-2 rounded hover:bg-slate-800">Sessions</a>
                <a href="/progress" class="block px-3 py-2 rounded hover:bg-slate-800">Progress</a>
                <a href="/admin" class="block px-3 py-2 rounded hover:bg-slate-800">Admin</a>
                <a href="/logout" class="block px-3 py-2 rounded hover:bg-slate-800 text-red-400">Logout</a>
            </nav>
        </aside>
        <main class="flex-1 p-6 space-y-6">
            <?php if ($message = get_flash('error')): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php echo $slot ?? '' ?>
        </main>
    </div>
</body>
</html>
