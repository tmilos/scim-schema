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

use Tmilos\ScimSchema\ScimConstants;

abstract class Schema extends Resource
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $description;

    /** @var Schema\Attribute[] */
    protected $attributes = [];

    public function getResourceType()
    {
        return ScimConstants::RESOURCE_TYPE_SCHEMA;
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
