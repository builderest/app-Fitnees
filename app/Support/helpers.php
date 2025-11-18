<?php

declare(strict_types=1);

use Illuminate\Support\Str;

if (! function_exists('title_case')) {
    function title_case(?string $value): string
    {
        return Str::title($value ?? '');
    }
}
