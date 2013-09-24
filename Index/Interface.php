<?php

/**
 * Storage Interface for Indexes
 */

namespace attitude\Elements\Storage;

use \attitude\Elements\Storage_Interface;

/**
 * Storage Interface for Indexes
 *
 * @author Martin Adamko <@martin_adamko>
 * @version v0.1.0
 * @licence MIT
 *
 */
interface Index_Interface extends Storage_Interface
{
    /**
     * Returns uniqueness of the Index
     *
     * @param   void
     * @returns bool
     *
     */
    public function is_unique();
}
