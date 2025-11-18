<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-950 text-white flex items-center justify-center min-h-screen">
    <form method="POST" action="/login" class="bg-slate-900 p-8 rounded-xl space-y-4 w-full max-w-md">
        <h1 class="text-2xl font-bold">Bienvenido</h1>
        <div>
            <label class="text-sm text-slate-400">Email</label>
            <input type="email" name="email" class="w-full mt-1 px-3 py-2 rounded bg-slate-800 border border-slate-700">
        </div>
        <div>
            <label class="text-sm text-slate-400">Password</label>
            <input type="password" name="password" class="w-full mt-1 px-3 py-2 rounded bg-slate-800 border border-slate-700">
        </div>
        <button class="w-full py-2 bg-indigo-600 rounded">Entrar</button>
        <p class="text-sm text-center text-slate-400">¿No tienes cuenta? <a href="/register" class="text-indigo-400">Regístrate</a></p>
    </form>
</body>
</html>
