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

use Tmilos\ScimSchema\Model\v2\ResourceType;
use Tmilos\ScimSchema\ScimConstants;
use Tmilos\ScimSchema\ScimConstantsV2;

class ResourceTypeBuilder
{
    private static $builderMap = [
        ScimConstants::RESOURCE_TYPE_RESOURCE_TYPE => 'buildResourceType',
        ScimConstants::RESOURCE_TYPE_SCHEMA => 'buildSchema',
        ScimConstants::RESOURCE_TYPE_SERVICE_PROVIDER_CONFIG => 'buildServiceProviderConfig',
        ScimConstants::RESOURCE_TYPE_USER => 'buildUser',
        ScimConstants::RESOURCE_TYPE_GROUP => 'buildGroup',
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
        $result = new ResourceType(ScimConstants::RESOURCE_TYPE_RESOURCE_TYPE);
        $result->setName('ResourceType');
        $result->setDescription('Resource Type');
        $result->setEndpoint('/ResourceTypes');
        $result->setSchema(ScimConstantsV2::SCHEMA_RESOURCE_TYPE);
        $result->getMeta()->setLocation($this->locationBase.'/ResourceTypes/'.$result->getId());

        return $result;
    }

    /**
     * @return ResourceType
     */
    public function buildSchema()
    {
        $result = new ResourceType(ScimConstants::RESOURCE_TYPE_SCHEMA);
        $result->setName('Schema');
        $result->setDescription('Schema');
        $result->setEndpoint('/Schemas');
        $result->setSchema(ScimConstantsV2::SCHEMA_SCHEMA);
        $result->getMeta()->setLocation($this->locationBase.'/ResourceTypes/'.$result->getId());

        return $result;
    }

    /**
     * @return ResourceType
     */
    public function buildServiceProviderConfig()
    {
        $result = new ResourceType(ScimConstants::RESOURCE_TYPE_SERVICE_PROVIDER_CONFIG);
        $result->setName('Service Provider Configuration');
        $result->setDescription('Service Provider Configuration');
        $result->setEndpoint('/ServiceProviderConfigs');
        $result->setSchema(ScimConstantsV2::SCHEMA_SERVICE_PROVIDER_CONFIG);
        $result->getMeta()->setLocation($this->locationBase.'/ResourceTypes/'.$result->getId());

        return $result;
    }

    /**
     * @return ResourceType
     */
    public function buildUser()
    {
        $result = new ResourceType(ScimConstants::RESOURCE_TYPE_USER);
        $result->setName('User');
        $result->setDescription('User Account');
        $result->setEndpoint('/Users');
        $result->setSchema(ScimConstantsV2::SCHEMA_USER);
        $result->addSchemaExtension(ScimConstantsV2::SCHEMA_ENTERPRISE_USER, false);
        $result->getMeta()->setLocation($this->locationBase.'/ResourceTypes/'.$result->getId());

        return $result;
    }

    /**
     * @return ResourceType
     */
    public function buildGroup()
    {
        $result = new ResourceType(ScimConstants::RESOURCE_TYPE_GROUP);
        $result->setName('Group');
        $result->setDescription('Group');
        $result->setEndpoint('/Groups');
        $result->setSchema(ScimConstantsV2::SCHEMA_GROUP);
        $result->getMeta()->setLocation($this->locationBase.'/ResourceTypes/'.$result->getId());

        return $result;
    }
}
