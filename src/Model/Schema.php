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

class Schema extends Resource
{
    const SCHEMA = 'urn:ietf:params:scim:schemas:core:2.0:Schema';
    const RESOURCE_TYPE = 'urn:ietf:params:scim:schemas:core:2.0:ResourceType';
    const SERVICE_PROVIDER_CONFIG = 'urn:ietf:params:scim:schemas:core:2.0:ServiceProviderConfig';
    const GROUP = 'urn:ietf:params:scim:schemas:core:2.0:Group';
    const USER = 'urn:ietf:params:scim:schemas:core:2.0:User';
    const ENTERPRISE_USER = 'urn:ietf:params:scim:schemas:extension:enterprise:2.0:User';

    /** @var string */
    protected $name;

    /** @var string */
    protected $description;

    /** @var Schema\Attribute[] */
    protected $attributes = [];

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct();
        $this->id = $id;
        $this->schemas = [static::SCHEMA];
        $this->meta = new Meta(ResourceType::SCHEMA);

        $this->attributes = [];
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
     * @param Schema\Attribute $attribute
     */
    public function addAttribute(Schema\Attribute $attribute)
    {
        $this->attributes[] = $attribute;
    }

    /**
     * @return Schema\Attribute[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     *
     * @return null|Schema\Attribute
     */
    public function findAttribute($name)
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }

        return null;
    }

    public function serializeObject()
    {
        $result = parent::serializeObject();
        $result['name'] = $this->name;
        $result['description'] = $this->description;
        $result['attributes'] = [];
        foreach ($this->attributes as $attribute) {
            $result['attributes'][] = $attribute->serializeObject();
        }

        return $result;
    }

    /**
     * @param array $data
     *
     * @return Schema
     */
    public static function deserializeObject(array $data)
    {
        /** @var Schema $result */
        $result = self::deserializeCommonAttributes($data);
        $result->name = $data['name'];
        $result->description = $data['description'];
        $result->attributes = [];
        foreach ($data['attributes'] as $attribute) {
            $result->attributes[] = Schema\Attribute::deserializeObject($attribute);
        }

        return $result;
    }
}
