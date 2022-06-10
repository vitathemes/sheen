# Sheen - [Demo](https://demo.vitathemes.com/sheen/)

Sheen is a a minimal & mobile-first theme. The best choice for people who want to publish their projects.

![Home Page](screenshot.png)

## Features

-   Sass for stylesheets
-   Compatible with [Contact Form 7](https://wordpress.org/plugins/contact-form-7/)
-   Compatible with [Newsletter](https://wordpress.org/plugins/newsletter/)
-   Theme options built directly into WordPress native live theme customizer
-   Responsive design
-   Cross-browser compatibility
-   Custom Google WebFonts
-   Child themes support
-   Developer friendly extendable code
-   Translation ready (with .POT files included)
-   SEO optimized
-   GNU GPL version 3.0 licensed
-   Support 3 level sub-menu
-   …and much more

See a working example at [demo.vitathemes.com/sheen](https://demo.vitathemes.com/sheen/).

## Theme installation

1. In your admin panel, go to Appearance > Themes and click the Add New button.
2. Click Upload Theme and Choose File, then select the theme's .zip file. Click Install Now.
3. Click Activate to use your new theme right away.
4. Active Recommended plugins at the top of dashboard ( `Kirki Customizer Framework` , `LibWp`).
5. After libWp plugin activated go to `Settings > Permalink` and select `Post name` (\*this option is recommended) radio button and save the changes.
6. You can add your projects from `Projects` section in dashboard.
7. Also for editing theme features you can go to Appearance > Customize and Customize theme. Also we highly recommend to Avtive `Kirki Customizer Framework` to have more editing features.

## Theme structure

```shell
themes/sheen/                # → Root of your theme
│── assets/                      # → Theme internal assets
│   ├── css/                     # → Compressed css file
│   ├── fonts/                   # → Theme default fonts ( Customizable from kirki )
│   ├── images/                  # → Theme compressed images
│   ├── js/                      # → Theme Minified javascript files
│   └── src/                     # → Theme source files
├── classes/                     # → Custom PHP classes
├── inc/                         # → Theme functions
│   ├── tgmpa/                   # → Tgmpa plugin recommendation
│   ├── customizer.php           # → All codes related to WordPress Customizer (We use Kirki Framework)
│   ├── custom-header.php        # → All codes related to WordPress Customizer (We use Kirki Framework)
│   ├── template-functions.php   # → Custom template tweaks
│   └── template-tags.php        # → Custom template tags
│   └── setup.php                # → Theme Setup
├── language/                    # → Theme Language files
├── page-template/               # → Theme Part files (Include) - Pages
├── template-parts/              # → Theme Part files (Include)
├── node_modules/                # → Node.js packages
├── package.json                 # → Node.js dependencies and scripts
```

## Theme setup

Edit `functions.php` to enable or disable theme features, setup navigation menus, post thumbnail sizes, and sidebars.

## Theme development

-   Run `npm install` from the theme directory to install dependencies
-   Change browserSyncTask from `sheen.local/` to whatever address that your project folder is
-   Run `gulp` from the root of theme directory and it's starting to watch any changes in scss files from the `sass` folder

## License

Sheen is licensed under [GNU GPL Version 3](LICENSE).

## ❤️ Sponsors
<a href="" target="_blank"><img width="200" src="https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.png"/></a>

Want to become a sponsor? you can sponsor & support VitaThemes by providing our team your service for free!
