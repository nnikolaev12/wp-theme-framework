# Nyxit Soft Theme Framework

## Introduction

This repository contains a starter theme framework for developing custom WordPress themes.

## Theme Features

This theme is enhanced with various features to speed up development. Here is a list of all the enhancements, features, and add-ons included:

- Improved theme structure
- Main config file to control theme front-end dependencies
- Registers navigations, sidebars, and CPT from config
- Includes front-end libraries from config
- Enables various theme supports from config
- Automated front-end resources generation and optimization with Gulp
- Helper PHP functions to use while developing the theme
- Improved debugging with VarDumper package
- Tailwind CSS integration

## Theme Structure

**/config/** - Theme configuration files. Includes WordPress specific configurations in config.php, and Tailwind config file.

**/src/** - Includes FE source files, such as scss, js, and images. All styles, scripts and images should be edited from [here](#scripts).

**/assets/** - Processed source files are added here. Do not edit those files directly or your changes will be overwritten. Exceptions are icons, fonts, and other static files that do need processing and are used directly.

**/scripts/** - Helper scripts to automate certain processes in the theme development. More on the available scripts here.

**/template-parts/** - Small theme building blocks - components, layout parts, Gutenberg blocks (ACF PRO), etc.

**/templates/** - WordPress page templates.

## Theme Config

Theme can be configured from the main configuration file located in the /config/ folder. All the options available are well documented inside the file itself for better referrence.

In addition, the tailwind.config.js file can be find in this folder as well. This file is the main configuration point for Tailwind. More information of the available options can be found in the official documentation: https://tailwindcss.com/docs/theme

## Gulp.js Automation

`gulp styles`

Builds CSS and Tailwind styles in the /assets/ directory.

`gulp scripts`

Builds JS scripts in the /assets/ directory.

`gulp images`

Gets all images from the /src/images directory and optimizes them. Adds a webp version of the image in the /assets/images directory.

`gulp watch`

Starts the develoment workflow that builds CSS, Tailwind, and JS on each file change. Loads BrowserSync that opens a new browser window with the project capable of hot-reloading

`gulp`

Runs styles, scripts, and images job.

## Debugging

`dd( $variable );`

Dumps a variable and stops execution.

`dump( $variable );`

Dumps a variable and continues execution.

`dump_log( $variable );`

Dumps a variable in the debug.log. Make sure that WordPress debugging is enabled from wp-config.php.

## Helper Functions

`Nyxit\Helper::url( string $path = "/", bool $echo = false );`

Get or echo the URL relative to the root domain. Leave empty to get the root domain.

`Nyxit\Helper::asset( string $path, bool $echo = true );`

Get or echo an asset from the assets directory. Specify a path to the file located in the /assets/ directory.

```
Nyxit\Helper::image( array(
    'name' => 'image_file_name',
    'alt' => 'Alt text',
    'width' => 200,
    'height' => 200,
    'class' => 'my-image-class',
) );
```

Echo an the HTML markup for an image located in the /assets/images/ directory. Specify the required params: path or filename as name without the file extension. Optionally specify alternative text, width, height, and class for the image. The image is rendered as webp. Images created with `gulp images` are automatically added in the /assets/images/ directory as webp.

`Nyxit\Helper::icon( string $filename );`

Echo an HTML markup for an inline SVG icon located in the /assets/icons/ directory. Specify the required param: filename without the extension.

`Nyxit\Helper::icon_img( string $filename );`

Echo an HTML markup for an SVG icon with the img tag located in the /assets/icons/ directory. Supply the following optional arguments as key => value for fine-tuning: width, height.

`Nyxit\Helper::component( string $filename, array $args = [] );`

Render a component from the template-parts/components folder.

`Nyxit\Helper::block( string $name, array $args = [] );`

Render a block from the template-parts/blocks folder (just like a component).

`Nyxit\Helper::is_plugin_active( string $plugin_id );`

Checks if a plugin is active. Plugin id is a string in the following format: plugin-folder/main-plugin-file.php. Returns boolean.

## <a name="scripts"></a>Scripts

1. Create the initial structure for a Guttenberg block (ACF PRO standard) by suppling a name of the block. Your newly created block resides in /template-parts/blocks folder. Additionally, you can specify some of the fields schema by supplying it via the `--fields` flag. All fields should be comma-separated and specified in the following manner: {NAME}:{TYPE}. See the examples below:

`php scripts/create-block.php {BLOCK_NAME}`

OR

`npm run create-block {BLOCK_NAME}`

With fields:

`npm run create-block {BLOCK_NAME} --fields title:text,description:textarea,profile_image:image`
