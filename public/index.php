<?php

declare(strict_types=1);

use WellRESTed\OpenAPI\Example\ContainerBuilder;
use WellRESTed\Server;

require_once '../vendor/autoload.php';

$builder = new ContainerBuilder();
$container = $builder->build();

$server = $container->get(Server::class);
$server->respond();
