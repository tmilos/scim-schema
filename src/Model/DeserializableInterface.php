<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Model;

interface DeserializableInterface
{
    /**
     * @param array $data
     *
     * @return object
     */
    public static function deserializeObject(array $data);
}
