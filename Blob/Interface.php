<?php

/**
 * Document Storage Interface
 */

namespace attitude\Elements\Storage;

use \attitude\Elements\Storage_Interface;

/**
 * Document Storage Interface
 *
 * Higher level of storage system representing key-value document storage.
 *
 * @author Martin Adamko <@martin_adamko>
 * @version v0.1.0
 * @licence MIT
 *
 */
interface Blob_Interface extends Storage_Interface
{
    /**
     * Generates a new Universally Unique IDentifiers
     *
     * v4
     *
     */
    public function uuid();

    /**
     * Stores new variable in the storage without knowing a key
     *
     * Storage::store() stores var in the storage returning key on success.
     *
     * @uses    Storage::uuid()
     * @uses    Storage::add()
     * @param   mixed   $var    The variable to store. Strings and integers are
     *                          stored as is, other types are stored serialized.
     * @returns mixed           Returns associated key on success or FALSE on
     *                          failure.
     *
     */
    public function store($var);
}
