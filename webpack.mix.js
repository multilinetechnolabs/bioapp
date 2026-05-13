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

const mix = require('laravel-mix');

let frameworks = [
    'angular',
    'bootstrap',
    'datatables',
    'jquery',
    'jquery-ui',
    'three'
 ]

const CleanWebpackPlugin = require('clean-webpack-plugin');
const shouldCleanBuildOutput = process.env.CLEAN_BUILD === '1';
const pathsToClean = [
    'public/assets',
    'public/css',
    'public/fonts',
    'public/images',
    'public/js'
];
const cleanOptions = { verbose: true, dry: false };

mix
   .extract(frameworks)
   .options({ processCssUrls: false })
   // Javascripts
   .js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/app/jquery.selectlistactions.js', 'public/js')
   .js('resources/assets/js/app/jplayer.playlist.js', 'public/js')
   .js('resources/assets/js/app/jquery.jplayer.js', 'public/js')
   // Stylesheets
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/app/admin.scss', 'public/css/app')
   .sass('resources/assets/sass/app/affiliate.scss', 'public/css/app')
   .sass('resources/assets/sass/app/homepage.scss', 'public/css/app')
   .sass('resources/assets/sass/app/landing_page.scss', 'public/css/app')
   .sass('resources/assets/sass/app/products.scss', 'public/css/app')
   .sass('resources/assets/sass/app/pricing.scss', 'public/css/app')
   .sass('resources/assets/sass/app/sessions.scss', 'public/css/app')
   .sass('resources/assets/sass/app/bioconnect.scss', 'public/css/app')
   .sass('resources/assets/sass/app/bioconnect/profile.scss', 'public/css/app/bioconnect')
   .sass('resources/assets/sass/app/bioconnect/groups.scss', 'public/css/app/bioconnect')
   .sass('resources/assets/sass/app/data_cache.scss', 'public/css/app')
   // Images
   .copyDirectory('resources/assets/images', 'public/images')
   .copyDirectory('node_modules/datatables/media/images', 'public/images')
   .copyDirectory('node_modules/jquery-ui/themes/base/images', 'public/css/images')
   // Fonts
   .copyDirectory('resources/assets/fonts', 'public/fonts')
   .copyDirectory('node_modules/font-awesome/fonts', 'public/fonts')
   // Raw runtime assets intentionally served from /assets
   .copyDirectory('resources/assets/3d_models', 'public/assets/3d_models')
   .copyDirectory('resources/assets/files', 'public/assets/files');

if (shouldCleanBuildOutput) {
   mix.webpackConfig({
      plugins: [
         new CleanWebpackPlugin(pathsToClean, cleanOptions),
      ]
   });
}
