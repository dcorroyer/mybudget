<?php

namespace Symfony\Config\ZenstruckFoundry;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class MakeFactoryConfig 
{
    private $defaultNamespace;
    private $_usedProperties = [];

    /**
     * Default namespace where factories will be created by maker.
     * @default 'Factory'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function defaultNamespace($value): static
    {
        $this->_usedProperties['defaultNamespace'] = true;
        $this->defaultNamespace = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('default_namespace', $value)) {
            $this->_usedProperties['defaultNamespace'] = true;
            $this->defaultNamespace = $value['default_namespace'];
            unset($value['default_namespace']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['defaultNamespace'])) {
            $output['default_namespace'] = $this->defaultNamespace;
        }

        return $output;
    }

}
