<?php

namespace Symfony\Config\ZenstruckFoundry;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class InstantiatorConfig 
{
    private $withoutConstructor;
    private $allowExtraAttributes;
    private $alwaysForceProperties;
    private $service;
    private $_usedProperties = [];

    /**
     * Whether or not to call an object's constructor during instantiation.
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function withoutConstructor($value): static
    {
        $this->_usedProperties['withoutConstructor'] = true;
        $this->withoutConstructor = $value;

        return $this;
    }

    /**
     * Whether or not to allow extra attributes.
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function allowExtraAttributes($value): static
    {
        $this->_usedProperties['allowExtraAttributes'] = true;
        $this->allowExtraAttributes = $value;

        return $this;
    }

    /**
     * Whether or not to skip setters and force set object properties (public/private/protected) directly.
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function alwaysForceProperties($value): static
    {
        $this->_usedProperties['alwaysForceProperties'] = true;
        $this->alwaysForceProperties = $value;

        return $this;
    }

    /**
     * Customize the instantiator service.
     * @example my_instantiator
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function service($value): static
    {
        $this->_usedProperties['service'] = true;
        $this->service = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('without_constructor', $value)) {
            $this->_usedProperties['withoutConstructor'] = true;
            $this->withoutConstructor = $value['without_constructor'];
            unset($value['without_constructor']);
        }

        if (array_key_exists('allow_extra_attributes', $value)) {
            $this->_usedProperties['allowExtraAttributes'] = true;
            $this->allowExtraAttributes = $value['allow_extra_attributes'];
            unset($value['allow_extra_attributes']);
        }

        if (array_key_exists('always_force_properties', $value)) {
            $this->_usedProperties['alwaysForceProperties'] = true;
            $this->alwaysForceProperties = $value['always_force_properties'];
            unset($value['always_force_properties']);
        }

        if (array_key_exists('service', $value)) {
            $this->_usedProperties['service'] = true;
            $this->service = $value['service'];
            unset($value['service']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['withoutConstructor'])) {
            $output['without_constructor'] = $this->withoutConstructor;
        }
        if (isset($this->_usedProperties['allowExtraAttributes'])) {
            $output['allow_extra_attributes'] = $this->allowExtraAttributes;
        }
        if (isset($this->_usedProperties['alwaysForceProperties'])) {
            $output['always_force_properties'] = $this->alwaysForceProperties;
        }
        if (isset($this->_usedProperties['service'])) {
            $output['service'] = $this->service;
        }

        return $output;
    }

}
