let mix = require('laravel-mix');

mix.css('./resources/css/reset.css', '/public/css/index.css')
  .css('./resources/css/normalize.css', '/public/css/index.css');

mix.sass('./resources/sass/index.scss', '/public/css/index.css');