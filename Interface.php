<?php

/**
 * Storage Interface
 */

namespace attitude\Elements;

/**
 * Storage Interface
 *
 * Low level storage system with basic CRUD methods.
 *
 * @author Martin Adamko <@martin_adamko>
 * @version v0.1.0
 * @licence MIT
 *
 */
interface Storage_Interface
{
    /**
     * Checks if the given key or index exists at the storage
     *
     * @param   string  $key    The key or array of keys to check.
     * @returns bool            Returns TRUE if key exists or FALSE on failure.
     *
     */
    public function exists($key);

    /**
     * Add an item to the storage
     *
     * Stores variable var with key only if such key doesn't exist at the
     * storage yet.
     *
     * @param   string  $key    The key that will be associated with the item.
     * @param   mixed   $var    The variable to store. Strings and integers are
     *                          stored as is, other types are stored serialized.
     * @param   int     $flag   Use STORAGE_COMPRESSED to store the item
     *                          compressed (uses zlib).
     * @param   int     $expire Expiration time of the item. If it's equal to
     *                          zero, the item will never expire. You can also
     *                          use Unix timestamp or a number of seconds
     *                          starting from current time, but in the latter
     *                          case the number of seconds may not exceed
     *                          2592000 (30 days).
     * @returns bool            Returns TRUE on success or FALSE on failure.
     *                          Returns FALSE if such key already exist. For the
     *                          rest Storage::add() behaves similarly to
     *                          Storage::set().
     */
    public function add($key, $var);

    /**
     * Returns previously stored data if an item with such key exists on the
     * storage at this moment.
     *
     * You can pass array of keys to Storage::get() to get array of values.
     * The result array will contain only found key-value pairs.
     *
     * @param   string  $key    The key or array of keys to fetch.
     * @param   int     $flag   Use STORAGE_COMPRESSED to store the item
     *                          compressed (uses zlib).
     * @param   int     $expire Expiration time of the item. If it's equal to
     *                          zero, the item will never expire. You can also
     *                          use Unix timestamp or a number of seconds
     *                          starting from current time, but in the latter
     *                          case the number of seconds may not exceed
     *                          2592000 (30 days).
     * @returns mixed           Returns the object associated with the key or an
     *                          array of found key-value pairs when key is an
     *                          array. Returns FALSE on failure, key is not
     *                          found or key is an empty array.
     *
     */
    public function get($key);

    /**
     * Store data at the storage
     *
     * Stores an item var with key on the storage. Parameter expire is
     * expiration time in seconds. If it's 0, the item never expires (but
     * storage doesn't guarantee this item to be stored all the time, it could
     * be deleted from the cache to make place for other items). You can use
     * STORAGE_COMPRESSED constant as flag value if you want to use on-the-fly
     * compression (uses zlib).
     *
     * Note: Remember that resource variables (i.e. file and connection
     * descriptors) cannot be stored in the cache, because they cannot be
     * adequately represented in serialized state.
     *
     * @param   string  $key    The key that will be associated with the item.
     * @param   mixed   $var    The variable to store. Strings and integers are
     *                          stored as is, other types are stored serialized.
     * @param   int     $flag   Use STORAGE_COMPRESSED to store the item
     *                          compressed (uses zlib).
     * @param   int     $expire Expiration time of the item. If it's equal to
     *                          zero, the item will never expire. You can also
     *                          use Unix timestamp or a number of seconds
     *                          starting from current time, but in the latter
     *                          case the number of seconds may not exceed
     *                          2592000 (30 days).
     * @returns bool            Returns TRUE on success or FALSE on failure.
     *
     */
    public function set($key, $value);

    /**
     * Replace value of the existing item
     *
     * Storage::replace() should be used to replace value of existing item with
     * key. In case if item with such key doesn't exists, Storage::replace()
     * returns FALSE. For the rest Storage::replace() behaves similarly to
     * Storage::set().
     *
     * @param   string  $key    The key that will be associated with the item.
     * @param   mixed   $var    The variable to store. Strings and integers are
     *                          stored as is, other types are stored serialized.
     * @param   int     $flag   Use STORAGE_COMPRESSED to store the item
     *                          compressed (uses zlib).
     * @param   int     $expire Expiration time of the item. If it's equal to
     *                          zero, the item will never expire. You can also
     *                          use Unix timestamp or a number of seconds
     *                          starting from current time, but in the latter
     *                          case the number of seconds may not exceed
     *                          2592000 (30 days).
     * @returns bool            Returns TRUE on success or FALSE on failure.
     *
     */
    public function replace($key, $value);

    /**
     * Delete item from the storage
     *
     * Storage::delete() deletes an item with the key.
     *
     * @param   string  $key    The key associated with the item to delete.
     * @returns bool            Returns TRUE on success or FALSE on failure.
     *
     */
    public function delete($key);
}
