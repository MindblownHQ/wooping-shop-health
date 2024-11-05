// webpack.mix.js

const mix = require('laravel-mix');

mix.disableSuccessNotifications();

mix.sass('assets/src/scss/admin.scss', 'assets/dist/css/admin.css')
   .options({
       processCssUrls: false
   });

mix.js('/assets/src/js/main.js', '/assets/dist/js/main.js');
