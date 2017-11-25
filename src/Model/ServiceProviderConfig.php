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

use Tmilos\ScimSchema\Model\SPC\Bulk;
use Tmilos\ScimSchema\ScimConstants;

abstract class ServiceProviderConfig extends Resource
{
    /** @var string */
    protected $documentationUri;

    /** @var SPC\Patch */
    protected $patch;

    /** @var SPC\Bulk */
    protected $bulk;

    /** @var SPC\Filter */
    protected $filter;

    /** @var SPC\ETag */
    protected $eTag;

    /** @var SPC\ChangePassword */
    protected $changePassword;

    /** @var SPC\Sort */
    protected $sort;

    /** @var SPC\AuthenticationScheme[] */
    protected $authenticationSchemes;

    /**
     * @param string                     $documentationUri
     * @param bool                       $patchSupported
     * @param bool                       $bulkSupported
     * @param int                        $bulkMaxOperations
     * @param int                        $bulkMaxPayloadSize
     * @param false                      $filterSupported
     * @param int                        $filterMaxResults
     * @param bool                       $eTagSupported
     * @param bool                       $changePasswordSupported
     * @param bool                       $sortSupported
     * @param SPC\AuthenticationScheme[] $authenticationSchemes
     */
    public function __construct($documentationUri, $patchSupported, $bulkSupported, $bulkMaxOperations, $bulkMaxPayloadSize, $filterSupported, $filterMaxResults, $eTagSupported, $changePasswordSupported, $sortSupported, array $authenticationSchemes)
    {
        parent::__construct('ServiceProviderConfig');

        $this->documentationUri = $documentationUri;
        $this->patch = new SPC\Patch($patchSupported);
        $this->bulk = new SPC\Bulk($bulkSupported, $bulkMaxOperations, $bulkMaxPayloadSize);
        $this->filter = new SPC\Filter($filterSupported, $filterMaxResults);
        $this->eTag = new SPC\ETag($eTagSupported);
        $this->changePassword = new SPC\ChangePassword($changePasswordSupported);
        $this->sort = new SPC\Sort($sortSupported);
        $this->authenticationSchemes = $authenticationSchemes;
    }

    public function getResourceType()
    {
        return ScimConstants::RESOURCE_TYPE_SERVICE_PROVIDER_CONFIG;
    }

    /**
     * @return string
     */
    public function getDocumentationUri()
    {
        return $this->documentationUri;
    }

    /**
     * @return SPC\Patch
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     * @return Bulk
     */
    public function getBulk()
    {
        return $this->bulk;
    }

    /**
     * @return SPC\Filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return SPC\ETag
     */
    public function getETag()
    {
        return $this->eTag;
    }

    /**
     * @return SPC\ChangePassword
     */
    public function getChangePassword()
    {
        return $this->changePassword;
    }

    /**
     * @return SPC\Sort
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @return SPC\AuthenticationScheme[]
     */
    public function getAuthenticationSchemes()
    {
        return $this->authenticationSchemes;
    }

    public function serializeObject()
    {
        $result = parent::serializeObject();
        unset($result['id']);

        if ($this->documentationUri) {
            $result['documentationUri'] = $this->documentationUri;
        }
        $result['patch'] = $this->getPatch()->serializeObject();
        $result['bulk'] = $this->getBulk()->serializeObject();
        $result['filter'] = $this->getFilter()->serializeObject();
        $result['etag'] = $this->getETag()->serializeObject();
        $result['changePassword'] = $this->getChangePassword()->serializeObject();
        $result['sort'] = $this->getSort()->serializeObject();
        $result['authenticationSchemes'] = [];
        foreach ($this->getAuthenticationSchemes() as $authenticationScheme) {
            $result['authenticationSchemes'][] = $authenticationScheme->serializeObject();
        }

        return $result;
    }

    /**
     * @param array $data
     *
     * @return ServiceProviderConfig
     */
    public static function deserializeObject(array $data)
    {
        /** @var ServiceProviderConfig $result */
        $result = self::deserializeCommonAttributes($data);

        if (isset($data['documentationUri'])) {
            $result->documentationUri = $data['documentationUri'];
        }
        $result->patch = SPC\Patch::deserializeObject($data['patch']);
        $result->bulk = SPC\Bulk::deserializeObject($data['bulk']);
        $result->filter = SPC\Filter::deserializeObject($data['filter']);
        $result->eTag = SPC\ETag::deserializeObject($data['etag']);
        $result->changePassword = SPC\ChangePassword::deserializeObject($data['changePassword']);
        $result->sort = SPC\Sort::deserializeObject($data['sort']);

        if (isset($data['authenticationSchemes'])) {
            foreach ($data['authenticationSchemes'] as $authenticationScheme) {
                $result->authenticationSchemes[] = SPC\AuthenticationScheme::deserializeObject($authenticationScheme);
            }
        }

        return $result;
    }
}
