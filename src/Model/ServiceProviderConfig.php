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
use Tmilos\ScimSchema\Model\SPC\Bulk;

class ServiceProviderConfig extends AbstractResource
{
    /** @var string */
    protected $documentationUri;

    /** @var bool */
    protected $patchSupported;

    /** @var bool */
    protected $bulkSupported;

    /** @var int */
    protected $bulkMaxOperations;

    /** @var int */
    protected $bulkMaxPayloadSize;

    /** @var false */
    protected $filterSupported;

    /** @var int */
    protected $filterMaxResults;

    /** @var bool */
    protected $eTagSupported;

    /** @var bool */
    protected $changePasswordSupported;

    /** @var bool */
    protected $sortSupported;

    /** @var array */
    protected $authenticationSchemes = [];

    // ------------------------

    /** @var SPC\Patch */
    private $patch;

    /** @var SPC\Bulk */
    private $bulk;

    /** @var SPC\Filter */
    private $filter;

    /** @var SPC\ETag */
    private $eTag;

    /** @var SPC\ChangePassword */
    private $changePassword;

    /** @var SPC\Sort */
    private $sort;

    /**
     * @param string $documentationUri
     * @param bool   $patchSupported
     * @param bool   $bulkSupported
     * @param int    $bulkMaxOperations
     * @param int    $bulkMaxPayloadSize
     * @param false  $filterSupported
     * @param int    $filterMaxResults
     * @param bool   $eTagSupported
     * @param bool   $changePasswordSupported
     * @param bool   $sortSupported
     * @param array  $authenticationSchemes
     */
    public function __construct($documentationUri, $patchSupported, $bulkSupported, $bulkMaxOperations, $bulkMaxPayloadSize, $filterSupported, $filterMaxResults, $eTagSupported, $changePasswordSupported, $sortSupported, array $authenticationSchemes)
    {
        parent::__construct('ServiceProviderConfig', [Schema::SERVICE_PROVIDER_CONFIG], ResourceType::SERVICE_PROVIDER_CONFIG);

        $this->documentationUri = $documentationUri;
        $this->patchSupported = (bool) $patchSupported;
        $this->bulkSupported = (bool) $bulkSupported;
        $this->bulkMaxOperations = (int) $bulkMaxOperations;
        $this->bulkMaxPayloadSize = (int) $bulkMaxPayloadSize;
        $this->filterSupported = (bool) $filterSupported;
        $this->filterMaxResults = (int) $filterMaxResults;
        $this->eTagSupported = (bool) $eTagSupported;
        $this->changePasswordSupported = (bool) $changePasswordSupported;
        $this->sortSupported = (bool) $sortSupported;
        $this->authenticationSchemes = $authenticationSchemes;
    }

    /**
     * @param array $arr
     *
     * @return ServiceProviderConfig
     */
    public static function fromArray(array $arr)
    {
        /** @var ServiceProviderConfig $result */
        $result = parent::fromArray($arr);

        if (isset($arr['documentationUri'])) {
            $result->documentationUri = $arr['documentationUri'];
        }

        if (isset($arr['patch']['supported'])) {
            $result->patchSupported = $arr['patch']['supported'];
        }

        if (isset($arr['bulk']['supported'])) {
            $result->bulkSupported = $arr['bulk']['supported'];
        }
        if (isset($arr['bulk']['maxOperations'])) {
            $result->bulkMaxOperations = $arr['bulk']['maxOperations'];
        }
        if (isset($arr['bulk']['maxPayloadSize'])) {
            $result->bulkMaxPayloadSize = $arr['bulk']['maxPayloadSize'];
        }

        if (isset($arr['filter']['supported'])) {
            $result->filterSupported = $arr['filter']['supported'];
        }
        if (isset($arr['filter']['maxResults'])) {
            $result->filterMaxResults = $arr['filter']['maxResults'];
        }

        if (isset($arr['etag']['supported'])) {
            $result->eTagSupported = $arr['etag']['supported'];
        }

        if (isset($arr['changePassword']['supported'])) {
            $result->changePasswordSupported = $arr['changePassword']['supported'];
        }

        if (isset($arr['sort']['supported'])) {
            $result->sortSupported = $arr['sort']['supported'];
        }

        if (isset($arr['authenticationSchemes'])) {
            foreach ($arr['authenticationSchemes'] as $authenticationScheme) {
                $result->authenticationSchemes[] = SPC\AuthenticationScheme::fromArray($authenticationScheme);
            }
        }

        return $result;
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
        if (!$this->patch) {
            $this->patch = new SPC\Patch($this->patchSupported);
        }

        return $this->patch;
    }

    /**
     * @return Bulk
     */
    public function getBulk()
    {
        if (!$this->bulk) {
            $this->bulk = new Bulk($this->bulkSupported, $this->bulkMaxOperations, $this->bulkMaxPayloadSize);
        }

        return $this->bulk;
    }

    /**
     * @return SPC\Filter
     */
    public function getFilter()
    {
        if (!$this->filter) {
            $this->filter = new SPC\Filter($this->filterSupported, $this->filterMaxResults);
        }

        return $this->filter;
    }

    /**
     * @return SPC\ETag
     */
    public function getETag()
    {
        if (!$this->eTag) {
            $this->eTag = new SPC\ETag($this->eTagSupported);
        }

        return $this->eTag;
    }

    /**
     * @return SPC\ChangePassword
     */
    public function getChangePassword()
    {
        if (!$this->changePassword) {
            $this->changePassword = new SPC\ChangePassword($this->changePasswordSupported);
        }

        return $this->changePassword;
    }

    /**
     * @return SPC\Sort
     */
    public function getSort()
    {
        if (!$this->sort) {
            $this->sort = new SPC\Sort($this->sortSupported);
        }

        return $this->sort;
    }

    /**
     * @return SPC\AuthenticationScheme[]|Collection
     */
    public function getAuthenticationSchemes()
    {
        if ($this->authenticationSchemes instanceof Collection) {
            return $this->authenticationSchemes;
        }

        if ($this->authenticationSchemes === null) {
            $this->authenticationSchemes = [];
        }
        foreach ($this->authenticationSchemes as $k => $v) {
            if (is_array($v)) {
                $this->authenticationSchemes[$k] = SPC\AuthenticationScheme::fromArray($v);
            }
        }

        return $this->authenticationSchemes;
    }

    public function toArray()
    {
        $result = parent::toArray();

        if ($this->documentationUri) {
            $result['documentationUri'] = $this->documentationUri;
        }
        $result['patch'] = $this->getPatch()->toArray();
        $result['bulk'] = $this->getBulk()->toArray();
        $result['filter'] = $this->getFilter()->toArray();
        $result['etag'] = $this->getETag()->toArray();
        $result['changePassword'] = $this->getChangePassword()->toArray();
        $result['sort'] = $this->getSort()->toArray();
        $result['authenticationSchemes'] = [];
        foreach ($this->getAuthenticationSchemes() as $authenticationScheme) {
            $result['authenticationSchemes'][] = $authenticationScheme->toArray();
        }

        return $result;
    }
}
