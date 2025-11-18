<div x-data="{ open: false }">
    <button @click="open = true" class="px-4 py-2 bg-slate-800 rounded"><?php echo $trigger ?? 'Abrir' ?></button>
    <div x-show="open" class="fixed inset-0 bg-black/60 flex items-center justify-center" x-cloak>
        <div class="bg-slate-900 p-6 rounded-xl w-full max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold"><?php echo $title ?? '' ?></h3>
                <button @click="open = false">✕</button>
            </div>
            <?php echo $slot ?? '' ?>
        </div>
    </div>
</div>
