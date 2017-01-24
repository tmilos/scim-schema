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

use Tmilos\ScimSchema\Helper;

abstract class AbstractResource
{
    /** @var string */
    protected $id;

    /** @var string[] */
    protected $schemas;

    /** @var string */
    protected $externalId;

    /** @var string */
    protected $metaResourceType;

    /** @var \DateTime */
    protected $metaCreated;

    /** @var \DateTime */
    protected $metaLastModified;

    /** @var string */
    protected $metaLocation;

    /** @var string */
    protected $metaVersion;

    /** @var Meta */
    private $meta;

    /**
     * @param array $arr
     *
     * @return static
     */
    public static function fromArray(array $arr)
    {
        $result = static::create($arr);
        if (isset($arr['externalId'])) {
            $result->externalId = $arr['externalId'];
        }
        if (isset($arr['meta']['created'])) {
            $result->metaCreated = Helper::string2dateTime($arr['meta']['created']);
        }
        if (isset($arr['meta']['lastModified'])) {
            $result->metaLastModified = Helper::string2dateTime($arr['meta']['lastModified']);
        }
        if (isset($arr['meta']['location'])) {
            $result->metaLocation = $arr['meta']['location'];
        }
        if (isset($arr['meta']['version'])) {
            $result->metaLocation = $arr['meta']['version'];
        }

        return $result;
    }

    /**
     * @param array $arr
     *
     * @return static
     */
    protected static function create(array $arr)
    {
        return new static($arr['id'], $arr['schemas'], $arr['meta']['resourceType']);
    }

    /**
     * @param string   $id
     * @param string[] $schemas
     * @param string   $resourceType
     */
    public function __construct($id, array $schemas, $resourceType)
    {
        $this->id = $id;
        $this->schemas = $schemas;
        $this->metaResourceType = $resourceType;
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
        if (!$this->meta) {
            $this->meta = new Meta($this->metaResourceType, $this->metaCreated, $this->metaLastModified, $this->metaLocation, $this->metaLastModified);
        }

        return $this->meta;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
            'schemas' => $this->schemas,
            'id' => $this->id,
        ];
        if ($this->externalId) {
            $result['externalId'] = $this->externalId;
        }
        $result['meta'] = $this->getMeta()->toArray();

        return $result;
    }
}
