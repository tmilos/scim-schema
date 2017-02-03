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

use Tmilos\ScimSchema\Model\v1\ServiceProviderConfig;

class ServiceProviderConfigBuilderV1 extends ServiceProviderConfigBuilder
{
    /**
     * @return ServiceProviderConfig
     */
    public function buildServiceProviderConfig()
    {
        $result = new ServiceProviderConfig(
            $this->documentationUri,
            $this->patchSupported,
            $this->bulkSupported,
            $this->bulkMaxOperations,
            $this->bulkMaxPayloadSize,
            $this->filterSupported,
            $this->filterMaxResults,
            $this->eTagSupported,
            $this->changePasswordSupported,
            $this->sortSupported,
            $this->authenticationSchemes
        );

        return $result;
    }
}
