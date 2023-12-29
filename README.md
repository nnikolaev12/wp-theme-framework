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

**/template-parts/** - Small theme building blocks - components, layout parts, Gutenberg blocks (ACF), etc.

**/templates/** - WordPress page templates.

## Theme Config

TODO

## Gulp.js Automation

`gulp styles`

Builds CSS and Tailwind styles in the /assets/ directory.

`gulp scripts`

Builds JS scripts in the /assets/ directory.

`gulp images`

Gets all images from the /src/ directory and optimizes them. Adds the optimized version and a webp version of the image in the /assets/ directory.

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

`\NyxitSoft\Helper::url( string $path = "/", bool $echo = false );`

Get or echo the URL relative to the root domain. Leave empty to get the root domain.

`\NyxitSoft\Helper::asset( string $path, bool $echo = true );`

Get or echo an asset from the assets directory. Specify a path to the file located in the /assets/ directory.

```
\NyxitSoft\Helper::image( array(
    'src' => 'path_to_the_image_file',
    'format' => 'png',
    'alt' => 'Alt text',
    'width' => 200,
    'height' => 200,
    'class' => 'my-image-class',
) );
```

Echo an the HTML markup for an image located in the /assets/images/ directory. Specify the required params: path or filename as src without the file extension and the format (png, jpg). Optionally specify alternative text, width, height, and class for the image. The image is rendered as webp with the source as a fallback.

`\NyxitSoft\Helper::icon( string $filename );`

Echo an HTML markup for an SVG icon located in the /assets/icons/ directory. Specify the required param: filename without the extension.

`\NyxitSoft\Helper::component( string $filename, array $args = [] );`

Render a previously created component from the template-parts/components folder.

## <a name="scripts"></a>Scripts

1. Create the initial structure for a Guttenberg block (ACF standard) by suppling a name of the block. Your newly created block resides in /template-parts/blocks folder.

`php scripts/create-block.php {BLOCK_NAME}`
