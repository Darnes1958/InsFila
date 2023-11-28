const colors = require('tailwindcss/colors')

 module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',

        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Filament/**/*.php'
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