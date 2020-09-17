const mix = require("laravel-mix");

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

mix.copy("resources/views/site/img", "public/site/img", false)

    // site
    .styles(
        [
            "resources/views/site/css/style.css",
            "resources/views/site/css/reset.css"
        ],
        "public/site/css/style.css"
    )
    .scripts(
        ["resources/views/site/js/scripts.js"],
        "public/site/js/scripts.js"
    )

    // Admin
    .styles(
        [
            "resources/views/admin/css/style.css",
            "resources/views/admin/css/reset.css"
        ],
        "public/admin/css/style.css"
    )
    .scripts(
        ["resources/views/admin/js/scripts.js"],
        "public/admin/js/scripts.js"
    )

    // Auth
    .styles(["resources/views/auth/css/style.css"], "public/auth/css/style.css")

    // global
    .copy("resources/img", "public/img", false)
    .copy("resources/css/fonts", "public/css/fonts", false)

    .scripts(["node_modules/jquery/dist/jquery.js"], "public/js/jquery.js")

    .styles("resources/css/common.css", "public/css/common.css")
    .styles("resources/css/icofont.css", "public/css/icofont.css")
    .sass(
        "node_modules/bootstrap/scss/bootstrap.scss",
        "public/css/bootstrap.css"
    )
    .scripts(
        ["node_modules/bootstrap/dist/js/bootstrap.bundle.js"],
        "public/js/bootstrap.js"
    )

    .version();
