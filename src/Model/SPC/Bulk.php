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

class Bulk extends AbstractSPCItem
{
    /** @var int */
    protected $maxOperations;

    /** @var int */
    protected $maxPayloadSize;

    /**
     * @param bool $supported
     * @param int  $maxOperations
     * @param int  $maxPayloadSize
     */
    public function __construct($supported, $maxOperations, $maxPayloadSize)
    {
        parent::__construct($supported);

        $this->maxOperations = $maxOperations;
        $this->maxPayloadSize = $maxPayloadSize;
    }

    /**
     * @return int
     */
    public function getMaxOperations()
    {
        return $this->maxOperations;
    }

    /**
     * @return int
     */
    public function getMaxPayloadSize()
    {
        return $this->maxPayloadSize;
    }

    public function serializeObject()
    {
        $result = parent::serializeObject();

        if ($this->isSupported()) {
            $result['maxOperations'] = $this->maxOperations;
            $result['maxPayloadSize'] = $this->maxPayloadSize;
        }

        return $result;
    }

    /**
     * @param array $data
     *
     * @return Bulk
     */
    public static function deserializeObject(array $data)
    {
        /** @var Bulk $result */
        $result = parent::deserializeObject($data);
        $result->maxOperations = $data['maxOperations'];
        $result->maxPayloadSize = $data['maxPayloadSize'];

        return $result;
    }
}
