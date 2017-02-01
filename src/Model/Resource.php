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

abstract class Resource implements SerializableInterface, DeserializableInterface
{
    /** @var string */
    protected $id;

    /** @var string[] */
    protected $schemas;

    /** @var string */
    protected $externalId;

    /** @var Meta */
    protected $meta;

    protected function __construct()
    {
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \string[]
     */
    public function getSchemas()
    {
        return $this->schemas;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @return Meta
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return array
     */
    public function serializeObject()
    {
        $result = [
            'schemas' => $this->schemas,
            'id' => $this->id,
        ];
        if ($this->externalId) {
            $result['externalId'] = $this->externalId;
        }
        $result['meta'] = $this->getMeta()->serializeObject();

        return $result;
    }

    /**
     * @param array $data
     *
     * @return static
     */
    protected static function deserializeCommonAttributes(array $data)
    {
        $result = new static();
        $result->id = $data['id'];
        $result->schemas = $data['schemas'];
        $result->meta = Meta::deserializeObject($data['meta']);
        if (isset($arr['externalId'])) {
            $result->externalId = $data['externalId'];
        }

        return $result;
    }
}
