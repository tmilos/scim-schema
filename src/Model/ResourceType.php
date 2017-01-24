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

class ResourceType extends AbstractResource
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
        parent::__construct($id, [Schema::RESOURCE_TYPE], static::RESOURCE_TYPE);
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

    public function toArray()
    {
        $result = parent::toArray();

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
}
