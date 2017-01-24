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

use Doctrine\Common\Collections\Collection;

class Schema extends AbstractResource
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

    /** @var Attribute[]|Collection */
    protected $attributes = [];

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        parent::__construct($id, [static::SCHEMA], ResourceType::SCHEMA);

        $this->attributes = new AttributeCollection();
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
     * @return Collection|Attribute[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     *
     * @return null|Attribute
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

    public function toArray()
    {
        $result = parent::toArray();
        $result['name'] = $this->name;
        $result['description'] = $this->description;
        $result['attributes'] = [];
        foreach ($this->attributes as $attribute) {
            $result['attributes'][] = $attribute->toArray();
        }

        return $result;
    }
}
