<?php

define('MODX_API_MODE', true);

require dirname(__FILE__, 4).'/public/index.php';

/* @var modX $modx */
/* @var modProcessorResponse $response */
$response = $modx->runProcessor('workspace/packages/scanlocal');
if ($response->isError()) {
    echo 'Error scanning local packages: '.$response->getMessage()."\n";
    die;
}


$q = $modx->newQuery('transport.modTransportPackage');
$q->where(array(
    'provider' => 0,
    'release' => 'noecrypt',
));
$q->sortby('version_major', 'DESC');
$q->sortby('version_minor', 'DESC');
$q->sortby('version_patch', 'DESC');


/* @var modTransportPackage $package */
if ($package = $modx->getObject('transport.modTransportPackage', $q)) {
    $signature = $package->get('signature');

    // Удаление пакета если был установлен
    echo "Removing package '{$signature}' \n";
    $package->uninstall();

    // Install the package
    if ($package->install()) {
        echo "Package installed successfully '{$signature}' \n";
    } else {
        echo "Error installing package '{$signature}' \n";
        exit;
    }
} else {
    echo "Package noecrypt not found \n";
    exit;
}

