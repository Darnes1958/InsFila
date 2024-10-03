import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    darkMode : 'class',
    content: [
        './resources/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/filament/user/pages/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ]
}

