<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Builder;

use Tmilos\ScimSchema\Model\ResourceType;
use Tmilos\ScimSchema\Model\Schema;

class ResourceTypeBuilder
{
    private static $builderMap = [
        ResourceType::RESOURCE_TYPE => 'buildResourceType',
        ResourceType::SCHEMA => 'buildSchema',
        ResourceType::SERVICE_PROVIDER_CONFIG => 'buildServiceProviderConfig',
        ResourceType::USER => 'buildUser',
        ResourceType::GROUP => 'buildGroup',
    ];

    private $locationBase = 'http://localhost';

    /**
     * @param string $locationBase
     */
    public function __construct($locationBase = 'http://localhost')
    {
        $this->setLocationBase($locationBase);
    }

    /**
     * @param string $locationBase
     */
    public function setLocationBase($locationBase)
    {
        $this->locationBase = rtrim($locationBase, '/');
    }

    /**
     * @param string $resourceTypeId
     *
     * @return ResourceType
     */
    public function build($resourceTypeId)
    {
        return $this->{self::$builderMap[$resourceTypeId]}();
    }

    /**
     * @return ResourceType
     */
    public function buildResourceType()
    {
        $result = new ResourceType(ResourceType::RESOURCE_TYPE);
        $result->setName('ResourceType');
        $result->setDescription('Resource Type');
        $result->setEndpoint('/ResourceTypes');
        $result->setSchema(Schema::RESOURCE_TYPE);
        $result->getMeta()->setLocation($this->locationBase.'/ResourceTypes/'.$result->getId());

        return $result;
    }

    /**
     * @return ResourceType
     */
    public function buildSchema()
    {
        $result = new ResourceType(ResourceType::SCHEMA);
        $result->setName('Schema');
        $result->setDescription('Schema');
        $result->setEndpoint('/Schemas');
        $result->setSchema(Schema::SCHEMA);
        $result->getMeta()->setLocation($this->locationBase.'/ResourceTypes/'.$result->getId());

        return $result;
    }

    /**
     * @return ResourceType
     */
    public function buildServiceProviderConfig()
    {
        $result = new ResourceType(ResourceType::SERVICE_PROVIDER_CONFIG);
        $result->setName('Service Provider Configuration');
        $result->setDescription('Service Provider Configuration');
        $result->setEndpoint('/ServiceProviderConfigs');
        $result->setSchema(Schema::SERVICE_PROVIDER_CONFIG);
        $result->getMeta()->setLocation($this->locationBase.'/ResourceTypes/'.$result->getId());

        return $result;
    }

    /**
     * @return ResourceType
     */
    public function buildUser()
    {
        $result = new ResourceType(ResourceType::USER);
        $result->setName('User');
        $result->setDescription('User Account');
        $result->setEndpoint('/Users');
        $result->setSchema(Schema::USER);
        $result->addSchemaExtension(Schema::ENTERPRISE_USER, false);
        $result->getMeta()->setLocation($this->locationBase.'/ResourceTypes/'.$result->getId());

        return $result;
    }

    /**
     * @return ResourceType
     */
    public function buildGroup()
    {
        $result = new ResourceType(ResourceType::GROUP);
        $result->setName('Group');
        $result->setDescription('Group');
        $result->setEndpoint('/Groups');
        $result->setSchema(Schema::GROUP);
        $result->getMeta()->setLocation($this->locationBase.'/ResourceTypes/'.$result->getId());

        return $result;
    }
}
