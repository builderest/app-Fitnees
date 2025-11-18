<?php ob_start(); ?>
<section class="bg-slate-900 p-6 rounded-xl space-y-4">
    <h1 class="text-2xl font-semibold">Nueva rutina</h1>
    <form method="POST" action="/routines" class="space-y-4">
        <div>
            <label class="text-sm text-slate-400">Título</label>
            <input type="text" name="title" class="w-full bg-slate-800 border border-slate-700 rounded">
        </div>
        <div>
            <label class="text-sm text-slate-400">Tipo</label>
            <select name="type" class="w-full bg-slate-800 border border-slate-700 rounded">
                <option value="push_pull_legs">Push / Pull / Legs</option>
                <option value="full_body">Full Body</option>
            </select>
        </div>
        <div>
            <label class="text-sm text-slate-400">Días (JSON)</label>
            <textarea name="days[]" class="w-full bg-slate-800 border border-slate-700 rounded h-40" placeholder='[{"title":"Día 1","exercises":[]}]'></textarea>
        </div>
        <button class="px-4 py-2 bg-indigo-600 rounded">Guardar</button>
    </form>
</section>
<?php $slot = ob_get_clean(); include resource_path('views/layouts/app.blade.php'); ?>
