<?php

namespace Symfony\Config\ZenstruckFoundry\DatabaseResetter;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class OdmConfig 
{
    private $objectManagers;
    private $_usedProperties = [];

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function objectManagers(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['objectManagers'] = true;
        $this->objectManagers = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('object_managers', $value)) {
            $this->_usedProperties['objectManagers'] = true;
            $this->objectManagers = $value['object_managers'];
            unset($value['object_managers']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['objectManagers'])) {
            $output['object_managers'] = $this->objectManagers;
        }

        return $output;
    }

}
