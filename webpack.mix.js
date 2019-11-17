const mix = require('laravel-mix');
var tailwindcss = require('tailwindcss');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/js/assets/scss/main.scss', 'public/css');
   
mix.postCss('resources/js/assets/css/main.css', 'public/css/main2.css', [
   tailwindcss('resources/js/tailwind.js'),
]);