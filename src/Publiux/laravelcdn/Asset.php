<?php

namespace Publiux\laravelcdn;

use Publiux\laravelcdn\Contracts\AssetInterface;

/**
 * Class Asset
 * Class Asset used to parse and hold all assets and
 * paths related data and configurations.
 *
 * @category DTO
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 */
class Asset implements AssetInterface
{
    /**
     * default [include] configurations.
     *
     * @var array
     */
    protected $default_include = [
        'directories' => ['public'],
        'extensions'  => [],
        'patterns'    => [],
    ];

    /**
     * default [exclude] configurations.
     *
     * @var array
     */
    protected $default_exclude = [
        'directories' => [],
        'files'       => [],
        'extensions'  => [],
        'patterns'    => [],
        'hidden'      => true,
    ];

    /**
     * @var array
     */
    protected $included_directories;

    /**
     * @var array
     */
    protected $included_files;

    /**
     * @var array
     */
    protected $included_extensions;

    /**
     * @var array
     */
    protected $included_patterns;

    /**
     * @var array
     */
    protected $excluded_directories;

    /**
     * @var array
     */
    protected $excluded_files;

    /**
     * @var array
     */
    protected $excluded_extensions;

    /**
     * @var array
     */
    protected $excluded_patterns;

    /*
     * @var boolean
     */
    protected $exclude_hidden;

    /*
     * Allowed assets for upload (found in included_directories)
     *
     * @var Collection
     */
    public $assets;

    /**
     * build a Asset object that contains the assets related configurations.
     *
     * @param array $configurations
     *
     * @return $this
     */
    public function init($configurations = [])
    {
        $this->parseAndFillConfiguration($configurations);

        $this->included_directories = $this->default_include['directories'];
        $this->included_extensions = $this->default_include['extensions'];
        $this->included_patterns = $this->default_include['patterns'];

        $this->excluded_directories = $this->default_exclude['directories'];
        $this->excluded_files = $this->default_exclude['files'];
        $this->excluded_extensions = $this->default_exclude['extensions'];
        $this->excluded_patterns = $this->default_exclude['patterns'];
        $this->exclude_hidden = $this->default_exclude['hidden'];

        return $this;
    }

    /**
     * Check if the config file has any missed attribute, and if any attribute
     * is missed will be overridden by a default attribute defined in this class.
     *
     * @param $configurations
     */
    private function parseAndFillConfiguration($configurations)
    {
        $this->default_include = isset($configurations['include']) ?
            array_merge($this->default_include, $configurations['include']) : $this->default_include;

        $this->default_exclude = isset($configurations['exclude']) ?
            array_merge($this->default_exclude, $configurations['exclude']) : $this->default_exclude;
    }

    /**
     * @return array
     */
    public function getIncludedDirectories()
    {
        return $this->included_directories;
    }

    /**
     * @return array
     */
    public function getIncludedExtensions()
    {
        return $this->included_extensions;
    }

    /**
     * @return array
     */
    public function getIncludedPatterns()
    {
        return $this->included_patterns;
    }

    /**
     * @return array
     */
    public function getExcludedDirectories()
    {
        return $this->excluded_directories;
    }

    /**
     * @return array
     */
    public function getExcludedFiles()
    {
        return $this->excluded_files;
    }

    /**
     * @return array
     */
    public function getExcludedExtensions()
    {
        return $this->excluded_extensions;
    }

    /**
     * @return array
     */
    public function getExcludedPatterns()
    {
        return $this->excluded_patterns;
    }

    /**
     * @return Collection
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @param mixed $assets
     */
    public function setAssets($assets)
    {
        $this->assets = $assets;
    }

    /**
     * @return mixed
     */
    public function getExcludeHidden()
    {
        return $this->exclude_hidden;
    }
}
