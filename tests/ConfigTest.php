<?php

namespace NettePhoenix\Tests;

use PHPUnit\Framework\TestCase;
use NettePhoenix\ConfigParser;

class ConfigTest extends TestCase
{
    public function testParseDefaultNetteConfigFiles(): void
    {
        $configParser = new ConfigParser(__DIR__ . '/fake/config');
        $this->assertInstanceOf(ConfigParser::class, $configParser->addMigrationDir(__DIR__ . '/fake/migration_dir_1'));
        $this->assertInstanceOf(ConfigParser::class, $configParser->addMigrationDir(__DIR__ . '/fake/migration_dir_2'));
        $expected = [
            'migration_dirs' => [
                __DIR__ . '/fake/migration_dir_1',
                __DIR__ . '/fake/migration_dir_2',
            ],
            'default_environment' => '',
            'log_table_name' => 'phoenix_log',
            'environments' => [
                'local' => [
                    'adapter' => 'mysql',
                    'host' => 'localhost',
                    'username' => 'phoenix',
                    'password' => '123',
                    'db_name' => 'phoenix',
                    'charset' => 'utf8',
                ],
                'production' => [
                    'adapter' => 'pgsql',
                    'host' => 'localhost',
                    'username' => 'pg_phoenix',
                    'password' => '12345',
                    'db_name' => 'pg_phoenix',
                    'charset' => 'utf8',
                    'port' => '5432',
                ],
            ],
        ];
        $this->assertEquals($expected, $configParser->createConfig());
    }

    public function testParseCustomNetteConfigFiles(): void
    {
        $configParser = new ConfigParser(__DIR__ . '/fake/config');
        $this->assertInstanceOf(ConfigParser::class, $configParser->addMigrationDir(__DIR__ . '/fake/migration_dir_1'));
        $this->assertInstanceOf(ConfigParser::class, $configParser->addMigrationDir(__DIR__ . '/fake/migration_dir_2'));
        $this->assertInstanceOf(ConfigParser::class, $configParser->addMigrationDir(__DIR__ . '/fake/migration_dir_3'));
        $this->assertInstanceOf(ConfigParser::class, $configParser->setDefaultEnvironment('local'));
        $this->assertInstanceOf(ConfigParser::class, $configParser->setLogTableName('custom_phoenix_log'));
        $expected = [
            'migration_dirs' => [
                __DIR__ . '/fake/migration_dir_1',
                __DIR__ . '/fake/migration_dir_2',
                __DIR__ . '/fake/migration_dir_3',
            ],
            'default_environment' => 'local',
            'log_table_name' => 'custom_phoenix_log',
            'environments' => [
                'local' => [
                    'adapter' => 'mysql',
                    'host' => 'localhost',
                    'username' => 'phoenix',
                    'password' => '123',
                    'db_name' => 'phoenix',
                    'charset' => 'utf8',
                ],
                'production' => [
                    'adapter' => 'pgsql',
                    'host' => 'localhost',
                    'username' => 'pg_phoenix',
                    'password' => '12345',
                    'db_name' => 'pg_phoenix',
                    'charset' => 'utf8',
                    'port' => '5432',
                ],
            ],
        ];
        $this->assertEquals($expected, $configParser->createConfig());
    }
}
