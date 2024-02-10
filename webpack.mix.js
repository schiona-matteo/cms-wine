const mix = require('laravel-mix');

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

mix.js('resources/js/site/app.js', 'public/js')
  .postCss("resources/css/site/app.css", "public/css", [
    require("tailwindcss"),
  ])
  .version()
  .vue();

mix.js('resources/js/backoffice/backoffice.js', 'public/js')
  .postCss("resources/css/backoffice/backoffice.css", "public/css", [
    require("tailwindcss"),
  ])
  .version()
  .vue();


  mix.postCss("resources/css/login.css", "public/css", [
    require("tailwindcss"),
  ]);