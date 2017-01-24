<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Model\SPC;

abstract class AbstractSPCItem
{
    /** @var bool */
    protected $supported;

    /**
     * @param bool $supported
     */
    public function __construct(&$supported)
    {
        $this->supported = &$supported;
    }

    /**
     * @return bool
     */
    public function isSupported()
    {
        return $this->supported;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'supported' => $this->supported,
        ];
    }
}
