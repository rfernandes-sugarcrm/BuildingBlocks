#!/usr/bin/env php
<?php
// Copyright 2016 SugarCRM Inc.  Licensed by SugarCRM under the Apache 2.0 license.


$packageID = "BuildingBlock_EnhancedWebLogicHooks";
$packageLabel = "BuildingBlocks: Enhanced Web Logic Hooks";
$supportedVersionRegex = '(26|25|14)\\..*$';
$acceptableSugarFlavors = array('ENT');
$description = 'Enhanced Web Logic Hooks.';
/******************************/

if (empty($argv[1])) {
    if (file_exists("version")) {
        $version = file_get_contents("version");
    }
} else {
    $version = $argv[1];
}

if (empty($version)){
    die("Use $argv[0] [version]\n");
}

$id = "{$packageID}-{$version}";

$directory = "releases";
if(!is_dir($directory)){
    mkdir($directory);
}

$zipFile = $directory . "/sugarcrm-{$id}.zip";


if (file_exists($zipFile)) {
    die("Error:  Release $zipFile already exists, so a new zip was not created. To generate a new zip, either delete the"
        . " existing zip file or update the version number in the version file AND then run the script to build the"
        . " module again. \n");
}

$manifest = array(
    'id' => $packageID,
    'name' => $packageLabel,
    'description' => $description,
    'version' => $version,
    'author' => 'SugarCRM, Inc.',
    'is_uninstallable' => 'true',
    'published_date' => date("Y-m-d H:i:s"),
    'type' => 'module',
    'remove_tables' => 'prompt',
    'acceptable_sugar_versions' => array(
        'exact_matches' => array(
        ),
        'regex_matches' => array(
            $supportedVersionRegex,
        ),
    ),
    'acceptable_sugar_flavors' => $acceptableSugarFlavors,
);

$installdefs = array(
    'id' => $packageID,
    'beans' => array (
        array (
            'module' => 'Enhanced_WebLogicHooks',
            'class' => 'Enhanced_WebLogicHooks',
            'path' => 'modules/Enhanced_WebLogicHooks/Enhanced_WebLogicHooks.php',
            'tab' => true,
        ),
        array (
                'module' => 'Enhanced_WebLogicHooksDelivery',
                'class' => 'Enhanced_WebLogicHooksDelivery',
                'path' => 'modules/Enhanced_WebLogicHooksMetadataFields/Enhanced_WebLogicHooksMetadataFields.php',
                'tab' => true,
        ),
        array (
                'module' => 'Enhanced_WebLogicHooksDelivery',
                'class' => 'Enhanced_WebLogicHooksDelivery',
                'path' => 'modules/Enhanced_WebLogicHooksDelivery/Enhanced_WebLogicHooksDelivery.php',
                'tab' => true,
        ),
    ),
    'language' => array (
        array (
            'from' => '<basepath>/src/modules/Enhanced_WebLogicHooks/language/en_us.lang.php',
            'to_module' => 'application',
            'language' => 'en_us',
        ),
        array (
                'from' => '<basepath>/src/modules/Enhanced_WebLogicHooksMetadataFields/language/en_us.lang.php',
                'to_module' => 'application',
                'language' => 'en_us',
        ),
        array (
                'from' => '<basepath>/src/modules/Enhanced_WebLogicHooksDelivery/language/en_us.lang.php',
                'to_module' => 'application',
                'language' => 'en_us',
        ),
    ),
);

echo "Creating {$zipFile} ... \n";
$zip = new ZipArchive();
$zip->open($zipFile, ZipArchive::CREATE);
$basePath = realpath('src/');
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($basePath, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);
foreach ($files as $name => $file) {
    if ($file->isFile()) {
        $fileReal = $file->getRealPath();
        $fileRelative = 'src' . str_replace($basePath, '', $fileReal);
        echo " [*] $fileRelative \n";
        $zip->addFile($fileReal, $fileRelative);
        $installdefs['copy'][] = array(
            'from' => '<basepath>/' . $fileRelative,
            'to' => preg_replace('/^src[\/\\\](.*)/', '$1', $fileRelative),
        );
    }
}
$manifestContent = sprintf(
    "<?php\n\$manifest = %s;\n\$installdefs = %s;\n",
    var_export($manifest, true),
    var_export($installdefs, true)
);
$zip->addFromString('manifest.php', $manifestContent);
$zip->close();
echo "Done creating {$zipFile}\n\n";
exit(0);
