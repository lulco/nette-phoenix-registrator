<?php

namespace NettePhoenix;

use Nette\Neon\Neon;
use Nette\Utils\Finder;

class ConfigParser
{
    private $configDir;
    
    private $migrationDirs = [];
    
    private $defaultEnvironment = '';
    
    private $logTableName = 'phoenix_log';
    
    public function __construct($configDir)
    {
        $this->configDir = $configDir;
    }
    
    public function addMigrationDir($migrationDir)
    {
        $this->migrationDirs[] = $migrationDir;
        return $this;
    }
    
    public function setDefaultEnvironment($defaultEnvironment)
    {
        $this->defaultEnvironment = $defaultEnvironment;
        return $this;
    }
    
    public function setLogTableName($logTableName)
    {
        $this->logTableName = $logTableName;
        return $this;
    }
    
    public function createConfig()
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
                'charset' => isset($dbData['charset']) ? $dbData['charset'] : 'utf8',
            ];
            if (isset($dbData['port'])) {
                $configData['environments'][$environment]['port'] = $dbData['port'];
            }
        }
        return $configData;
    }
}
