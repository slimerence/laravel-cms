let mix = require('laravel-mix');

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

// 前端
mix.js('resources/views/frontend/custom/blog_zoi/theme/js/jquery.animsition.min.js', 'public/js')
    .js('resources/views/frontend/custom/blog_zoi/theme/js/jquery.fullPage.min.js', 'public/js')
    .js('resources/views/frontend/custom/blog_zoi/theme/js/royal_preloader.min.js', 'public/js')
    .js('resources/views/frontend/custom/blog_zoi/theme/js/jquery.fancybox.pack.js', 'public/js')
    // .js('resources/views/frontend/custom/blog_zoi/theme/js/form.js', 'public/js')
    // .js('resources/views/frontend/custom/blog_zoi/theme/js/wow.min.js', 'public/js')
    .js('resources/views/frontend/custom/blog_zoi/theme/js/tweecoolmi.js', 'public/js')
    .js('resources/views/frontend/custom/blog_zoi/theme/js/jquery.mb-comingsoon.min.js', 'public/js')
    .js('resources/views/frontend/custom/blog_zoi/theme/js/typed.js', 'public/js')
    .js('resources/views/frontend/custom/blog_zoi/theme/js/plugins.js', 'public/js')
    .js('resources/views/frontend/custom/blog_zoi/theme/js/scripts.js', 'public/js')
    .js('resources/views/frontend/custom/_custom.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/views/frontend/custom/_custom.scss', 'public/css');

// combine all css/js into a single css/js
mix.styles([
    'public/css/_custom.css'
], 'public/css/all.css');
mix.scripts([
    // 'public/js/isotope.pkgd.min.js',
    // 'public/js/imagesloaded.pkgd.min.js',
    'public/js/jquery.animsition.min.js',
    // 'public/js/jquery.fullPage.min.js',
    // 'public/js/royal_preloader.min.js',
    'public/js/jquery.fancybox.pack.js',
    // 'public/js/form.js',
    // 'public/js/wow.min.js',
    // 'public/js/tweecoolmi.js',
    'public/js/jquery.mb-comingsoon.min.js',
    'public/js/typed.js',
    'public/js/plugins.js',
    'public/js/scripts.js',
    'public/js/_custom.js'
], 'public/js/all.js');
// 最终加载两个文件， all.css 和 all.js

// 后台
mix.js('resources/assets/js/backend.js', 'public/js')
    .sass('resources/assets/sass/backend.scss', 'public/css');

/*
*   拷贝图片等
*/
mix.copyDirectory(
    ['resources/assets/images'],
    'public/images'
);
