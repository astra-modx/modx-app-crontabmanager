<?php

define('MODX_API_MODE', true);

require dirname(__FILE__, 4).'/public/index.php';


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
$q->sortby('created', 'DESC');
/* @var modTransportPackage $package */
if ($objectList = $modx->getCollection('transport.modTransportPackage', $q)) {
    foreach ($objectList as $package) {
        $signature = $package->get('signature');

        // Удаление пакета если был установлен
        echo "Removing package '{$signature}' \n";
        $package->uninstall();

        echo "Destroy package '{$signature}' \n";
        $package->remove();
    }
} else {
    echo "Package noecrypt not found \n";
    exit;
}
