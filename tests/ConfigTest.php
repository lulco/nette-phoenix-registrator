<?php

namespace NettePhoenix\Tests;

use Nette\Configurator;
use PHPUnit\Framework\TestCase;
use NettePhoenix\ConfigParser;

class ConfigTest extends TestCase
{
    public function testParseTwoDefaultNetteConfigFiles(): void
    {
        $configurator = new Configurator();
        $configurator->addConfig(__DIR__ . '/fake/config/config.production.neon');
        $configurator->addConfig(__DIR__ . '/fake/config/config.local.neon');

        $configurator->setTempDirectory(__DIR__);
        $container = $configurator->createContainer();
        $configParser = new ConfigParser($container);
        $configParser->addMigrationDir(__DIR__ . '/fake/migration_dir_1');
        $configParser->addMigrationDir(__DIR__ . '/fake/migration_dir_2');
        $expected = [
            'migration_dirs' => [
                __DIR__ . '/fake/migration_dir_1',
                __DIR__ . '/fake/migration_dir_2',
            ],
            'default_environment' => 'local',
            'log_table_name' => 'phoenix_log',
            'environments' => [
                'local' => [
                    'adapter' => 'mysql',
                    'host' => 'localhost',
                    'username' => 'phoenix',
                    'password' => 123,
                    'db_name' => 'phoenix',
                    'charset' => 'utf8mb4',
                    'port' => 5432,
                    'collation' => 'utf8mb4_general_ci'
                ],
            ],
        ];
        $this->assertEquals($expected, $configParser->createConfig());
    }

    public function testParseDefaultNetteConfigFiles(): void
    {
        $configurator = new Configurator();
        $configurator->addConfig(__DIR__ . '/fake/config/config.local.neon');
        $configurator->setTempDirectory(__DIR__);
        $container = $configurator->createContainer();
        $configParser = new ConfigParser($container);
        $configParser->addMigrationDir(__DIR__ . '/fake/migration_dir_1');
        $expected = [
            'migration_dirs' => [
                __DIR__ . '/fake/migration_dir_1',
            ],
            'default_environment' => 'local',
            'log_table_name' => 'phoenix_log',
            'environments' => [
                'local' => [
                    'adapter' => 'mysql',
                    'host' => 'localhost',
                    'username' => 'phoenix',
                    'password' => 123,
                    'db_name' => 'phoenix',
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_general_ci'
                ],
            ],
        ];
        $this->assertEquals($expected, $configParser->createConfig());
    }
}
