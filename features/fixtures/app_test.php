<?php

use Symfony\Component\HttpFoundation\Request;

$loader = require __DIR__.'/../../vendor/autoload.php';
$loader->add('App', __DIR__);
$loader->add('Knp', __DIR__.'/bundles');

require __DIR__.'/AppKernel.php';

$kernel = new AppKernel('test', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
