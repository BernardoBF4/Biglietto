let mix = require('laravel-mix');

mix.css('./resources/css/reset.css', '/public/css/cms/index.css');

mix.sass('./resources/sass/cms/index.scss', '/public/css/cms/index.css');