const mix = require('laravel-mix')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .js('resources/js/app.js', 'public/js')
  .js('resources/js/games-form.js', 'public/js')
  .js('resources/js/games-list.js', 'public/js')
  .js('resources/js/pre-tests.js', 'public/js')
  .js('resources/js/jukebox.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .sass('resources/sass/dictionary.scss', 'public/css')
  .sass('resources/sass/games.scss', 'public/css')
  .sass('resources/sass/pre_tests.scss', 'public/css')
  .sass('resources/sass/podcasts.scss', 'public/css')
