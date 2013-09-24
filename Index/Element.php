<?php

namespace attitude\Elements\Storage;

use \attitude\Elements\DependencyContainer;

use \attitude\Elements\Column_Prototype;

abstract class Index_Element implements Index_Interface
{
    protected $column  = null;
    protected $storage_engine = null;

    /**
     * Sets Index as unique
     *
     * @var bool
     *
     */
    protected $unique = false;

    protected function __construct()
    {
        $this->unique = !! DependencyContainer::get(get_called_class().'.unique');

        $this->setNamespace(DependencyContainer::get(get_called_class().'.namespace'));
        $this->setStorageEngine(DependencyContainer::get(get_called_class().'.storage_engine'));
        $this->setColumn(DependencyContainer::get(get_called_class().'.column'));

        return $this;
    }

    private function setNamespace($dependency)
    {
        if (!is_string($dependency)) {
            trigger_error('Storage namespace must be a non empty string', E_USER_ERROR);
        }

        if (preg_match_all('|[^a-z0-9_]+|', $dependency, $devnull)) {
            trigger_error('Only `[a-z0-9_]` characters are allowed in storage namespace string.', E_USER_ERROR);
        }

        unset($devnull); // Optional since 5.4

        if (strlen(trim($dependency))===0) {
            trigger_error('Storage namespace cannot be an empty string.', E_USER_ERROR);
        }

        $this->namespace = $dependency;

        return $this;
    }

    private function setStorageEngine(Index_AwareInterface $dependency)
    {
        $this->storage_engine = $dependency;
    }

    private function setColumn(Column_Prototype $dependency)
    {
        $this->column = $dependency;
    }

    /**
     * Returns uniqueness of the Index
     *
     * @param   void
     * @returns bool
     *
     */
    public function is_unique()
    {
        return !!$this->storage_engine->is_unique();
    }

    /**
     * Checks if single index or array of indexes exist
     *
     * @param   string|array  $key  The key or array of keys to fetch.
     * @returns bool                Returns TRUE on success or FALSE on failure.
     *
     */
    public function exists($key, $var='*')
    {
        return $this->storage_engine->exists($key, $var);
    }

    public function get($key, $var='*')
    {
        return $this->storage_engine->get($key, $var);
    }

    public function add($key, $var)
    {
        return $this->storage_engine->add($key, $var);
    }

    public function set($key, $var)
    {
        return $this->storage_engine->set($key, $var);
    }

    public function replace($key, $var)
    {
        return $this->storage_engine->replace($key, $var);
    }

    public function delete($key, $var='*')
    {
        return $this->storage_engine->delete($key, $var);
    }
}
