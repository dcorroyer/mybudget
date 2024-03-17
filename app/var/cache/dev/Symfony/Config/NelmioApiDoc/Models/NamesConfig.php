<?php

namespace Symfony\Config\NelmioApiDoc\Models;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class NamesConfig 
{
    private $alias;
    private $type;
    private $groups;
    private $areas;
    private $_usedProperties = [];

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function alias($value): static
    {
        $this->_usedProperties['alias'] = true;
        $this->alias = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function type($value): static
    {
        $this->_usedProperties['type'] = true;
        $this->type = $value;

        return $this;
    }

    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function groups(mixed $value = NULL): static
    {
        $this->_usedProperties['groups'] = true;
        $this->groups = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function areas(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['areas'] = true;
        $this->areas = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('alias', $value)) {
            $this->_usedProperties['alias'] = true;
            $this->alias = $value['alias'];
            unset($value['alias']);
        }

        if (array_key_exists('type', $value)) {
            $this->_usedProperties['type'] = true;
            $this->type = $value['type'];
            unset($value['type']);
        }

        if (array_key_exists('groups', $value)) {
            $this->_usedProperties['groups'] = true;
            $this->groups = $value['groups'];
            unset($value['groups']);
        }

        if (array_key_exists('areas', $value)) {
            $this->_usedProperties['areas'] = true;
            $this->areas = $value['areas'];
            unset($value['areas']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['alias'])) {
            $output['alias'] = $this->alias;
        }
        if (isset($this->_usedProperties['type'])) {
            $output['type'] = $this->type;
        }
        if (isset($this->_usedProperties['groups'])) {
            $output['groups'] = $this->groups;
        }
        if (isset($this->_usedProperties['areas'])) {
            $output['areas'] = $this->areas;
        }

        return $output;
    }

}
