<?php

namespace Mehio\WhmUtil\IpReporter\Dbal;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;

class Database
{
    public function getEntityManager(): EntityManagerInterface
    {
        $ormConfig = Setup::createAnnotationMetadataConfiguration(
            [__DIR__ . '/../Entity'],
            '1' === $_ENV['DEV'],
            null,
            null,
            false
        );

        $databaseParameters = [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../../whm_ip_reporter.sqlite',
        ];

        return EntityManager::create($databaseParameters, $ormConfig);
    }
}
