const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
.js('resources/js/user_survey.js', 'public/js').vue()
.copy('resources/js/classes/CsvFile.js', 'public/js')
.copy('resources/js/classes/LocalFileReader.js', 'public/js')
.copy('resources/js/classes/CsvValidator.js', 'public/js')
    // .postCss('resources/css/app.css', 'public/css', [
    //     require('postcss-import'),
    //     require('tailwindcss'),
    //     require('autoprefixer'),
    // ])

if (mix.inProduction()) {
    mix.version();
}

