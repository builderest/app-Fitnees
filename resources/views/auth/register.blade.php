<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-950 text-white flex items-center justify-center min-h-screen">
    <form method="POST" action="/register" class="bg-slate-900 p-8 rounded-xl space-y-4 w-full max-w-2xl grid grid-cols-2 gap-4">
        <h1 class="text-2xl font-bold col-span-2">Crear cuenta</h1>
        <div>
            <label class="text-sm text-slate-400">Nombre</label>
            <input type="text" name="name" class="w-full mt-1 px-3 py-2 rounded bg-slate-800 border border-slate-700">
        </div>
        <div>
            <label class="text-sm text-slate-400">Email</label>
            <input type="email" name="email" class="w-full mt-1 px-3 py-2 rounded bg-slate-800 border border-slate-700">
        </div>
        <div>
            <label class="text-sm text-slate-400">Password</label>
            <input type="password" name="password" class="w-full mt-1 px-3 py-2 rounded bg-slate-800 border border-slate-700">
        </div>
        <div>
            <label class="text-sm text-slate-400">Meta</label>
            <select name="training_goal" class="w-full mt-1 px-3 py-2 rounded bg-slate-800 border border-slate-700">
                <option value="fat_loss">Perder grasa</option>
                <option value="muscle_gain">Ganar músculo</option>
                <option value="maintain">Mantener</option>
                <option value="performance">Performance</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-slate-400">Nivel</label>
            <select name="training_level" class="w-full mt-1 px-3 py-2 rounded bg-slate-800 border border-slate-700">
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-slate-400">Edad</label>
            <input type="number" name="age" class="w-full mt-1 px-3 py-2 rounded bg-slate-800 border border-slate-700">
        </div>
        <div>
            <label class="text-sm text-slate-400">Peso</label>
            <input type="number" name="weight" class="w-full mt-1 px-3 py-2 rounded bg-slate-800 border border-slate-700">
        </div>
        <div class="col-span-2">
            <button class="w-full py-3 bg-indigo-600 rounded">Crear cuenta</button>
        </div>
    </form>
</body>
</html>
