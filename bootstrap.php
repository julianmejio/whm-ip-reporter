<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/.env');

$entityManager = (new \Mehio\WhmUtil\IpReporter\Dbal\Database())->getEntityManager();