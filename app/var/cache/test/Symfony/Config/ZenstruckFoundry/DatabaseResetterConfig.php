<?php

namespace Symfony\Config\ZenstruckFoundry;

require_once __DIR__.\DIRECTORY_SEPARATOR.'DatabaseResetter'.\DIRECTORY_SEPARATOR.'OrmConfig.php';
require_once __DIR__.\DIRECTORY_SEPARATOR.'DatabaseResetter'.\DIRECTORY_SEPARATOR.'OdmConfig.php';

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class DatabaseResetterConfig 
{
    private $enabled;
    private $orm;
    private $odm;
    private $_usedProperties = [];

    /**
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function enabled($value): static
    {
        $this->_usedProperties['enabled'] = true;
        $this->enabled = $value;

        return $this;
    }

    public function orm(array $value = []): \Symfony\Config\ZenstruckFoundry\DatabaseResetter\OrmConfig
    {
        if (null === $this->orm) {
            $this->_usedProperties['orm'] = true;
            $this->orm = new \Symfony\Config\ZenstruckFoundry\DatabaseResetter\OrmConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "orm()" has already been initialized. You cannot pass values the second time you call orm().');
        }

        return $this->orm;
    }

    public function odm(array $value = []): \Symfony\Config\ZenstruckFoundry\DatabaseResetter\OdmConfig
    {
        if (null === $this->odm) {
            $this->_usedProperties['odm'] = true;
            $this->odm = new \Symfony\Config\ZenstruckFoundry\DatabaseResetter\OdmConfig($value);
        } elseif (0 < \func_num_args()) {
            throw new InvalidConfigurationException('The node created by "odm()" has already been initialized. You cannot pass values the second time you call odm().');
        }

        return $this->odm;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('enabled', $value)) {
            $this->_usedProperties['enabled'] = true;
            $this->enabled = $value['enabled'];
            unset($value['enabled']);
        }

        if (array_key_exists('orm', $value)) {
            $this->_usedProperties['orm'] = true;
            $this->orm = new \Symfony\Config\ZenstruckFoundry\DatabaseResetter\OrmConfig($value['orm']);
            unset($value['orm']);
        }

        if (array_key_exists('odm', $value)) {
            $this->_usedProperties['odm'] = true;
            $this->odm = new \Symfony\Config\ZenstruckFoundry\DatabaseResetter\OdmConfig($value['odm']);
            unset($value['odm']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['enabled'])) {
            $output['enabled'] = $this->enabled;
        }
        if (isset($this->_usedProperties['orm'])) {
            $output['orm'] = $this->orm->toArray();
        }
        if (isset($this->_usedProperties['odm'])) {
            $output['odm'] = $this->odm->toArray();
        }

        return $output;
    }

}
