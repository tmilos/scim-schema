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

abstract class Resource extends SchemaBase implements SerializableInterface, DeserializableInterface
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $schemaId;

    /** @var string */
    protected $externalId;

    /** @var Meta */
    protected $meta;

    /** @var array|ResourceExtension[] schemaId => extension */
    private $extensions = [];

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->schemaId = $this->getSchemaId();
        $this->meta = new Meta($this->getResourceType());
    }

    /**
     * @return string
     */
    abstract public function getResourceType();

    /**
     * @return \string[]
     */
    public function getSchemas()
    {
        return array_merge([static::getSchemaId()], array_keys($this->extensions));
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     * @return array|ResourceExtension[] schemaId => extension
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @param array|ResourceExtension $extension
     * @param string                  $schemaId
     */
    public function addExtension($extension, $schemaId = null)
    {
        if (!($extension instanceof ResourceExtension || is_array($extension))) {
            throw new \InvalidArgumentException('Resource extension must extend ResourceExtension or be an array');
        }

        if ($extension instanceof ResourceExtension) {
            $schemaId = $extension->getSchemaId();
        } elseif (is_array($extension) && !$schemaId) {
            throw new \InvalidArgumentException('SchemaId must be specified if extension is added as array');
        }

        if ($schemaId === static::getSchemaId()) {
            throw new \InvalidArgumentException(sprintf('Resource already has such main schema "%s"', $schemaId));
        }

        $this->extensions[$schemaId] = $extension;
    }

    /**
     * @return array
     */
    public function serializeObject()
    {
        $result = [
            'schemas' => $this->getSchemas(),
            'id' => $this->getId(),
        ];
        if ($this->getExternalId()) {
            $result['externalId'] = $this->getExternalId();
        }
        $result['meta'] = $this->getMeta()->serializeObject();
        foreach ($this->extensions as $schemaId => $extension) {
            if (!$extension) {
                continue;
            }
            if ($extension instanceof ResourceExtension) {
                $result[$schemaId] = $extension->serializeObject();
            } elseif (is_array($extension)) {
                $result[$schemaId] = $extension;
            } else {
                throw new \InvalidArgumentException('Resource extension must extend ResourceExtension or be an array');
            }
        }

        return $result;
    }

    /**
     * @param array $data
     *
     * @return static
     */
    protected static function deserializeCommonAttributes(array $data)
    {
        $result = new static(isset($data['id']) ? $data['id'] : null);
        if ($result->getResourceType() != $data['meta']['resourceType']) {
            throw new \RuntimeException(sprintf('Error deserializing resource type "%s" into class "%s"', $data['meta']['resourceType'], get_class($result)));
        }
        $result->meta = Meta::deserializeObject($data['meta']);
        if (isset($arr['externalId'])) {
            $result->externalId = $data['externalId'];
        }
        if (isset($data['schemas'])) {
            foreach ($data['schemas'] as $schemaId) {
                if ($schemaId != $result->getSchemaId()) {
                    $result->extensions[$schemaId] = isset($data[$schemaId]) ? $data[$schemaId] : null;
                }
            }
        }

        return $result;
    }
}
