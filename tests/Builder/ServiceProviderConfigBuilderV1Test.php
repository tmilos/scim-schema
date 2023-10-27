<?php

namespace Tests\Tmilos\ScimSchema\Builder;

use PHPUnit\Framework\TestCase;
use Tests\Tmilos\ScimSchema\TestHelper;
use Tmilos\ScimSchema\Builder\ServiceProviderConfigBuilderV1;

class ServiceProviderConfigBuilderV1Test extends TestCase
{
    public function test_builds_default_service_provider_config()
    {
        $builder = new ServiceProviderConfigBuilderV1();
        $spc = $builder->buildServiceProviderConfig();
        $arr = $spc->serializeObject();

        $this->assertEquals(TestHelper::getExpected('v1.service_provider_config.default.json'), $arr);
    }
}
