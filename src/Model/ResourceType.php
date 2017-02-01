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

class ResourceType extends Resource
{
    const SCHEMA = 'Schema';
    const RESOURCE_TYPE = 'ResourceType';
    const SERVICE_PROVIDER_CONFIG = 'ServiceProviderConfig';
    const GROUP = 'Group';
    const USER = 'User';

    /** @var string */
    protected $name;

    /** @var string */
    protected $description;

    /** @var string */
    protected $endpoint;

    /** @var string */
    protected $schema;

    /**
     * schema => required.
     *
     * @var array
     */
    protected $schemaExtensions;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct();

        $this->id = $id;
        $this->schemas = [Schema::RESOURCE_TYPE];
        $this->meta = new Meta(static::RESOURCE_TYPE);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param string $schema
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
    }

    /**
     * schema => required.
     *
     * @return string[]
     */
    public function getSchemaExtensions()
    {
        return $this->schemaExtensions;
    }

    /**
     * @param string $schema
     * @param bool   $required
     */
    public function addSchemaExtension($schema, $required)
    {
        $this->schemaExtensions[(string) $schema] = (bool) $required;
    }

    /**
     * @param string $schema
     */
    public function removeSchemaExtension($schema)
    {
        unset($this->schemaExtensions[(string) $schema]);
    }

    public function serializeObject()
    {
        $result = parent::serializeObject();

        $result['name'] = $this->name;
        $result['description'] = $this->description;
        $result['endpoint'] = $this->endpoint;
        $result['schema'] = $this->schema;
        if ($this->schemaExtensions) {
            $result['schemaExtensions'] = [];
            foreach ($this->schemaExtensions as $schema => $required) {
                $result['schemaExtensions'][] = [
                    'schema' => $schema,
                    'required' => (bool) $required,
                ];
            }
        }

        return $result;
    }

    /**
     * @param array $data
     *
     * @return ResourceType
     */
    public static function deserializeObject(array $data)
    {
        /** @var ResourceType $result */
        $result = self::deserializeCommonAttributes($data);
        $result->name = $data['name'];
        $result->description = $data['description'];
        $result->endpoint = $data['endpoint'];
        $result->schema = $data['schema'];
        if (isset($data['schemaExtensions'])) {
            $result->schemaExtensions = [];
            foreach ($data['schemaExtensions'] as $schemaExtension) {
                $result->schemaExtensions[$schemaExtension['schema']] = $schemaExtension['required'];
            }
        }

        return $result;
    }
}
