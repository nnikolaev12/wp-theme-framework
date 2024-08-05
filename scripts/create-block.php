<?php
#!/usr/bin/env php


// Check if required arguments are provided
if ($argc < 2) {
    echo "Usage: php create-block.php [block name]\n";
    echo "Usage with NPM: npm run create-block [block name]\n";
    echo "Optional fields setting: npm run create-block [block name] --fields title:text\n";
    echo "Check the themes README.md for more information.\n";
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

    \$data = ! empty( \$args ) ? \$args : get_fields();
    \$block_classes = ! empty( \$block['className'] ) ? \$block['className'] : "";
    ?>
    <section class="$blockName <?php echo \$block_classes; ?>">

    </section>
    EOT;

    fwrite($blockTemplateFile, $blockTemplateContents);
    fclose($blockTemplateFile);
    echo "$blockName.php file created successfully.\n";
} else {
    echo "Error! Unable to create $blockName.php file.\n";
    exit(1);
}

/**
 * Create ACF json file with specified fields
 * 
 * TODO: Add support for repeater fields
 */
function format_fields( string $fields )
{
    $fieldsArray = explode( ",", $fields );
    $formattedFields = "";

    foreach ( $fieldsArray as $index => $field ) {
        $fieldArray = explode( ":", $field );
        $name = $fieldArray[0];
        $type = $fieldArray[1];
        $label = ucwords( str_replace( "_", " ", $name ) );
        $hash = uniqid();
        $comma = $index + 1 < count( $fieldsArray ) ? "," : "";

        $formattedFields .= <<<EOT
        {
            "key": "field_$hash",
            "label": "$label",
            "name": "$name",
            "aria-label": "",
            "type": "$type",
            "instructions": "",
            "required": 0,
            "conditional_logic": 0,
            "wrapper": {
                "width": "",
                "class": "",
                "id": ""
            },
            "default_value": "",
            "maxlength": "",
            "placeholder": "",
            "prepend": "",
            "append": ""
        }$comma
        EOT;
    }

    return $formattedFields;
}

if ( $argc === 3 ) {
    $fields = format_fields( $argv[2] );

    $timestamp = time();
    $fileName = "group_block_$blockName" . "_" . uniqid();
    $acfJsonFile = fopen( "./acf-json/$fileName.json", "w" );

    if ( $acfJsonFile ) {
        // Add contents to the ACF json file
        $acfJsonContents = <<<EOT
        {
            "key": "$fileName",
            "title": "[Block] $capitalizedBlockName",
            "fields": [
                $fields
            ],
            "location": [
                [
                    {
                        "param": "block",
                        "operator": "==",
                        "value": "acf/$blockName"
                    }
                ]
            ],
            "menu_order": 0,
            "position": "normal",
            "style": "default",
            "label_placement": "top",
            "instruction_placement": "label",
            "hide_on_screen": "",
            "active": true,
            "description": "",
            "show_in_rest": 0,
            "modified": $timestamp
        }
        EOT;

        fwrite($acfJsonFile, $acfJsonContents);
        fclose($acfJsonFile);
        echo "acf-$fileName.json file created successfully.\n";
    } else {
        echo "Error! Unable to create acf-$fileName.json file.\n";
        exit(1);
    }
}
?>