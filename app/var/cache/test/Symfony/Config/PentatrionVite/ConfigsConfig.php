<?php

namespace Symfony\Config\PentatrionVite;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class ConfigsConfig 
{
    private $buildDirectory;
    private $scriptAttributes;
    private $linkAttributes;
    private $preloadAttributes;
    private $_usedProperties = [];

    /**
     * @default 'build'
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function buildDirectory($value): static
    {
        $this->_usedProperties['buildDirectory'] = true;
        $this->buildDirectory = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function scriptAttributes(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['scriptAttributes'] = true;
        $this->scriptAttributes = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function linkAttributes(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['linkAttributes'] = true;
        $this->linkAttributes = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed> $value
     *
     * @return $this
     */
    public function preloadAttributes(ParamConfigurator|array $value): static
    {
        $this->_usedProperties['preloadAttributes'] = true;
        $this->preloadAttributes = $value;

        return $this;
    }

    public function __construct(array $value = [])
    {
        if (array_key_exists('build_directory', $value)) {
            $this->_usedProperties['buildDirectory'] = true;
            $this->buildDirectory = $value['build_directory'];
            unset($value['build_directory']);
        }

        if (array_key_exists('script_attributes', $value)) {
            $this->_usedProperties['scriptAttributes'] = true;
            $this->scriptAttributes = $value['script_attributes'];
            unset($value['script_attributes']);
        }

        if (array_key_exists('link_attributes', $value)) {
            $this->_usedProperties['linkAttributes'] = true;
            $this->linkAttributes = $value['link_attributes'];
            unset($value['link_attributes']);
        }

        if (array_key_exists('preload_attributes', $value)) {
            $this->_usedProperties['preloadAttributes'] = true;
            $this->preloadAttributes = $value['preload_attributes'];
            unset($value['preload_attributes']);
        }

        if ([] !== $value) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($value)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['buildDirectory'])) {
            $output['build_directory'] = $this->buildDirectory;
        }
        if (isset($this->_usedProperties['scriptAttributes'])) {
            $output['script_attributes'] = $this->scriptAttributes;
        }
        if (isset($this->_usedProperties['linkAttributes'])) {
            $output['link_attributes'] = $this->linkAttributes;
        }
        if (isset($this->_usedProperties['preloadAttributes'])) {
            $output['preload_attributes'] = $this->preloadAttributes;
        }

        return $output;
    }

}
