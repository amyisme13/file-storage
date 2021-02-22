const ESLintPlugin = require('eslint-webpack-plugin');
const mix = require('laravel-mix');
const path = require('path');

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

const basePath = process.env.MIX_BASE_PATH;
if (basePath) {
  mix
    .webpackConfig({ output: { publicPath: basePath + '/' } })
    .setResourceRoot(basePath);
}

if (mix.inProduction()) {
  mix.version();
}

mix.webpackConfig({
  output: {
    chunkFilename: '[name].[chunkhash:5].js',
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js/'),
    },
  },
  plugins: [
    new ESLintPlugin({
      files: 'resources/js/**/*',
      extensions: ['js', 'ts'],
    }),
  ],
});

mix
  .vue()
  .ts('resources/js/app.ts', 'public/js')
  .postCss('resources/css/app.css', 'public/css', [require('tailwindcss')]);
