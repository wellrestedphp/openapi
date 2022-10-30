<?php

declare(strict_types=1);

use WellRESTed\OpenAPI\Components\Document;
use WellRESTed\OpenAPI\Components\Info;
use WellRESTed\OpenAPI\Components\Operation;
use WellRESTed\OpenAPI\Components\Path;

require_once '../vendor/autoload.php';

$document = new Document();
$document->info = new Info();
$document->info->title = 'My API';
$document->info->version = '1.0.1';

$cats = new Path();
$cats->get = new Operation();

$document->paths['/cats/'] = $cats;

$dogs = new Path();
$dogs->get = new Operation();

$document->paths['/dogs/'] = $dogs;

header('Content-type: application/json');
print json_encode($document, JSON_PRETTY_PRINT);

