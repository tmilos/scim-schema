<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Model\v2;

use Tmilos\ScimSchema\Model\Resource;
use Tmilos\ScimSchema\ScimConstants;
use Tmilos\ScimSchema\ScimConstantsV2;

class ResourceType extends Resource
{
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

    public function getResourceType()
    {
        return ScimConstants::RESOURCE_TYPE_RESOURCE_TYPE;
    }

    public function getSchemaId()
    {
        return ScimConstantsV2::SCHEMA_RESOURCE_TYPE;
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
