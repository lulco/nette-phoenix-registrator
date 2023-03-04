<?php

namespace NettePhoenix;

use InvalidArgumentException;
use Nette\DI\Container;

final class ConfigParser
{
    private const ENVIRONMENT = 'local';

    private $container;

    private $migrationDirs = [];

    private $logTableName = 'phoenix_log';

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function addMigrationDir(string $migrationDir, string $dirName = null): ConfigParser
    {
        if (!$dirName) {
            $this->migrationDirs[] = $migrationDir;
            return $this;
        }

        if (isset($this->migrationDirs[$dirName])) {
            throw new InvalidArgumentException('Migration dir with name "' . $dirName . '" already exists');
        }

        $this->migrationDirs[$dirName] = $migrationDir;
        return $this;
    }

    public function setLogTableName(string $logTableName): ConfigParser
    {
        $this->logTableName = $logTableName;
        return $this;
    }

    public function createConfig(): array
    {
        $configData = [
            'migration_dirs' => $this->migrationDirs,
            'default_environment' => self::ENVIRONMENT,
            'log_table_name' => $this->logTableName,
        ];
        $parameters = $this->container->getParameters();
        $dbData = $parameters['database']['default'];
        $charset = $dbData['charset'] ?? ($dbData['adapter'] === 'mysql' ? 'utf8mb4' : 'utf8');
        $configData['environments'][self::ENVIRONMENT] = [
            'adapter' => $dbData['adapter'],
            'host' => $dbData['host'],
            'username' => $dbData['user'],
            'password' => $dbData['password'],
            'db_name' => $dbData['dbname'],
            'charset' => $charset,
            'collation' => $dbData['collation'] ?? ($dbData['adapter'] === 'mysql' && $charset === 'utf8mb4' ? 'utf8mb4_general_ci' : null),
        ];
        if (isset($dbData['port'])) {
            $configData['environments'][self::ENVIRONMENT]['port'] = $dbData['port'];
        }
        return $configData;
    }
}
