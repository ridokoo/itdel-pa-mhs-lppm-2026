<?php

namespace App\Helper;

trait WithComponentNameHelper
{
    public static function getComponentName(): string
    {
        $class = static::class;

        // Hilangkan namespace App\Livewire\
        $name = str_replace('App\\Livewire\\', '', $class);
        // "Beranda\BerandaLivewire"

        // Convert \ ke . DAN convert PascalCase ke kebab-case
        $name = preg_replace_callback('/(\\\\|\.|(?<=[a-z])(?=[A-Z]))/',
            function ($matches) {
                return $matches[0] === '\\' || $matches[0] === '.' ? '.' : '-';
            },
            $name);
        // "Beranda.Beranda-Livewire"

        // Lowercase semua
        return strtolower($name);
        // "beranda.beranda-livewire"
    }
}



