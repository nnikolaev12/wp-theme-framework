<?php
#!/usr/bin/env php


// Check if both arguments are provided
if ($argc != 2) {
    echo "Usage: php create-block.php [block name]\n";
    exit(1);
}

$blockName = $argv[1];

// Create block folder
$blockFolder = "./template-parts/blocks/$blockName";
mkdir($blockFolder);

/**
 * Create block.json file
 */
$blockJsonFile = fopen( "$blockFolder/block.json", "w" );

if ( $blockJsonFile ) {
    // Add contents to the block.json file
    $capitalizedBlockName = ucwords($blockName);
    $blockJsonContents = <<<EOT
    {
        "name": "acf/$blockName",
        "title": "$capitalizedBlockName",
        "description": "$capitalizedBlockName section",
        "category": "theme",
        "icon": "sticky",
        "keywords": ["$blockName", "nyxit"],
        "acf": {
        "mode": "preview",
        "renderTemplate": "$blockName.php"
        },
        "align": "full"
    }
    EOT;

    fwrite($blockJsonFile, $blockJsonContents);
    fclose($blockJsonFile);
    echo "block.json file created successfully.\n";
} else {
    echo "Error! Unable to create block.json file.\n";
    exit(1);
}

/**
 * Create block template file
 */
$blockTemplateFile = fopen( "$blockFolder/$blockName.php", "w" );

if ( $blockTemplateFile ) {
    // Add contents to the block template file
    $blockTemplateContents = <<<EOT
<?php
/**
 * ACF Block: $capitalizedBlockName
 * 
 * @var array \$data
 */

\$data = ! empty( \$args ) ? \$args : \$block['data'];
?>
<section>

</section>
EOT;

fwrite($blockTemplateFile, $blockTemplateContents);
fclose($blockTemplateFile);
echo "$blockName.php file created successfully.\n";

} else {
echo "Error! Unable to create $blockName.php file.\n";
exit(1);
}
?>