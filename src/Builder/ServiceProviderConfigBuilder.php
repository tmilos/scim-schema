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

use Tmilos\ScimSchema\Model\ServiceProviderConfig;
use Tmilos\ScimSchema\Model\SPC\AuthenticationScheme;

abstract class ServiceProviderConfigBuilder
{
    /** @var string */
    private $locationBase = 'http://localhost';

    /** @var string */
    protected $documentationUri;

    /** @var bool */
    protected $patchSupported = false;

    /** @var bool */
    protected $bulkSupported = false;

    /** @var int */
    protected $bulkMaxOperations = 100;

    /** @var int */
    protected $bulkMaxPayloadSize = 1048576;

    /** @var false */
    protected $filterSupported = false;

    /** @var int */
    protected $filterMaxResults = 100;

    /** @var bool */
    protected $eTagSupported = false;

    /** @var bool */
    protected $changePasswordSupported = false;

    /** @var bool */
    protected $sortSupported = false;

    /** @var array */
    protected $authenticationSchemes = [];

    /**
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
     * @return ServiceProviderConfig
     */
    abstract public function buildServiceProviderConfig();

    /**
     * @param string $documentationUri
     *
     * @return ServiceProviderConfigBuilder
     */
    public function setDocumentationUri($documentationUri)
    {
        $this->documentationUri = $documentationUri;

        return $this;
    }

    /**
     * @param bool $patchSupported
     *
     * @return ServiceProviderConfigBuilder
     */
    public function setPatchSupported($patchSupported)
    {
        $this->patchSupported = (bool) $patchSupported;

        return $this;
    }

    /**
     * @param bool $bulkSupported
     *
     * @return ServiceProviderConfigBuilder
     */
    public function setBulkSupported($bulkSupported)
    {
        $this->bulkSupported = (bool) $bulkSupported;

        return $this;
    }

    /**
     * @param int $bulkMaxOperations
     *
     * @return ServiceProviderConfigBuilder
     */
    public function setBulkMaxOperations($bulkMaxOperations)
    {
        $this->bulkMaxOperations = (int) $bulkMaxOperations;

        return $this;
    }

    /**
     * @param int $bulkMaxPayloadSize
     *
     * @return ServiceProviderConfigBuilder
     */
    public function setBulkMaxPayloadSize($bulkMaxPayloadSize)
    {
        $this->bulkMaxPayloadSize = (int) $bulkMaxPayloadSize;

        return $this;
    }

    /**
     * @param false $filterSupported
     *
     * @return ServiceProviderConfigBuilder
     */
    public function setFilterSupported($filterSupported)
    {
        $this->filterSupported = (bool) $filterSupported;

        return $this;
    }

    /**
     * @param int $filterMaxResults
     *
     * @return ServiceProviderConfigBuilder
     */
    public function setFilterMaxResults($filterMaxResults)
    {
        $this->filterMaxResults = (int) $filterMaxResults;

        return $this;
    }

    /**
     * @param bool $eTagSupported
     *
     * @return ServiceProviderConfigBuilder
     */
    public function setETagSupported($eTagSupported)
    {
        $this->eTagSupported = (bool) $eTagSupported;

        return $this;
    }

    /**
     * @param bool $changePasswordSupported
     *
     * @return ServiceProviderConfigBuilder
     */
    public function setChangePasswordSupported($changePasswordSupported)
    {
        $this->changePasswordSupported = (bool) $changePasswordSupported;

        return $this;
    }

    /**
     * @param bool $sortSupported
     *
     * @return ServiceProviderConfigBuilder
     */
    public function setSortSupported($sortSupported)
    {
        $this->sortSupported = (bool) $sortSupported;

        return $this;
    }

    /**
     * @param AuthenticationScheme $authenticationScheme
     *
     * @return ServiceProviderConfigBuilder
     */
    public function addAuthenticationScheme(AuthenticationScheme $authenticationScheme)
    {
        $this->authenticationSchemes[] = $authenticationScheme;

        return $this;
    }
}
