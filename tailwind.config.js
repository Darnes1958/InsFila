import preset from './vendor/filament/support/tailwind.config.preset'
const colors = require('tailwindcss/colors')

 module.exports = {
     presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',

        './app/Livewire/**/*.php',
        './resources/views/livewire/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                // you can either spread `colors` to apply all the colors
                ...colors,

                // or add them one by one and name whatever you want

            }
        }
    }
}