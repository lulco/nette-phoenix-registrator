<?php

namespace NettePhoenix;

use InvalidArgumentException;
use Nette\Neon\Neon;
use Nette\Utils\Finder;

final class ConfigParser
{
    private $configDir;

    private $migrationDirs = [];

    private $defaultEnvironment = '';

    private $logTableName = 'phoenix_log';

    public function __construct(string $configDir)
    {
        $this->configDir = $configDir;
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

    public function setDefaultEnvironment(string $defaultEnvironment): ConfigParser
    {
        $this->defaultEnvironment = $defaultEnvironment;
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
            'default_environment' => $this->defaultEnvironment,
            'log_table_name' => $this->logTableName,
        ];
        foreach (Finder::findFiles('config.*.neon')->in($this->configDir) as $configFile) {
            $neon = Neon::decode(file_get_contents($configFile->getRealPath()));

            if (!$neon) {
                continue;
            }
            $environment = substr($configFile->getBaseName(), 7, -5);
            if (!isset($neon['parameters']['database']['default'])) {
                continue;
            }
            $dbData = $neon['parameters']['database']['default'];
            $configData['environments'][$environment] = [
                'adapter' => $dbData['adapter'],
                'host' => $dbData['host'],
                'username' => $dbData['user'],
                'password' => $dbData['password'],
                'db_name' => $dbData['dbname'],
                'charset' => $dbData['charset'] ?? ($dbData['adapter'] === 'mysql' ? 'utf8mb4' : 'utf8'),
            ];
            if (isset($dbData['port'])) {
                $configData['environments'][$environment]['port'] = $dbData['port'];
            }
        }
        return $configData;
    }
}
