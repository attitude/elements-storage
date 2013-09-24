<?php

namespace attitude\Elements\Storage;

use \attitude\Elements\DependencyContainer;

use \attitude\Elements\Storage_IndexableInterface;

abstract class Blob_Element implements Blob_Interface, Storage_IndexableInterface
{
    protected $namespace = null;
    protected $blob_storage = null;
    protected $index_storages = array();

    /**
     * List of index keys
     *
     * @var array
     */
    protected static $indexes = array();

    protected function __construct()
    {
        $this->setNamespace(DependencyContainer::get(get_called_class().'.namespace'));
        $this->setBlobStorage(DependencyContainer::get(get_called_class().'.blob_storage('.$this->namespace.')'));

        // Invoke indexes storage dependency
        foreach (static::$indexes as $index) {
            $this->setIndexStorage($index, DependencyContainer::get(get_called_class().'.indexes_storage['.$index.']'));
        }

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

    private function setBlobStorage(Blob_AwareInterface $dependency)
    {
        $this->blob_storage = $dependency;
    }

    private function setIndexStorage($index, Index_Interface $dependency)
    {
        if (!is_string($index)) {
            trigger_error('Index name must be a non empty string', E_USER_ERROR);
        }

        if (preg_match_all('|[^a-z0-9_]+|', $index, $devnull)) {
            trigger_error('Only `[a-z0-9_]` characters are allowed in index name string.', E_USER_ERROR);
        }

        unset($devnull); // Optional since 5.4

        $this->index_storages[$index] = $dependency;

        return $this;
    }

    /**
     * Returns Universally Unique IDentifier
     *
     * See https://gist.github.com/dahnielson/508447
     *
     * @param   void
     * @returns string  32 bit hexadecimal hash
     *
     */
    public function uuid()
    {
        return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',

        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function store($var)
    {
        $key = $this->uuid();

        $var['_id'] = $key;

        ksort($var);

        if ($this->add($key, $var)) {
            return $key;
        }

        return false;
    }

    public function exists($key)
    {
        return $this->blob_storage->exists($key);
    }

    public function add($key, $var)
    {
        if ($this->exists($key)) {
            return false;
        }

        return $this->set($key, $var);
    }

    public function get($key)
    {
        return $this->blob_storage->get($key);
    }

    public function find()
    {
        return $this->blob_storage->find();
    }

    public function set($key, $var)
    {
        // Handle indexing (unique first)
        foreach ($this->index_storages as $field => &$index_storage) {
            if (!$index_storage->is_unique()) {
                continue;
            }

            if (!$index_storage->set($key, $var[$field])) {
                throw new HTTPException(403, 'A `'.$field.'` must be unique.');

                return false;
            }
        }

        // Than regular indexes
        foreach ($this->index_storages as $field => &$index_storage) {
            if ($index_storage->is_unique()) {
                continue;
            }

            if (!$index_storage->set($key, $var[$field])) {
                throw new HTTPException('Failed to set index on `'.$field.'` field for `'.$key.'` key.');

                return false;
            }
        }

        $var['_id'] = $key;

        // No success on set
        if (!$this->blob_storage->set($key, $var)) {
            throw new HTTPException('Failed to store `'.$key.'` key document.');

            return false;
        }

        return $key;
    }

    public function replace($key, $var)
    {
        if (!$this->exists($key)) {
            return false;
        }

        return $this->set($key, $var);
    }

    public function delete($key)
    {
        if (!$this->exists($key)) {
            return null;
        }

        // Handle deleting of indexes
        foreach ($this->index_storages as &$index_storage) {
            if (!$index_storage->delete($key)) {
                return false;
            }
        }

        return $this->blob_storage->delete($key);
    }
}
