<?php

namespace Tests\Tmilos\ScimSchema\Builder;

use Tests\Tmilos\ScimSchema\TestHelper;
use Tmilos\ScimSchema\Builder\ServiceProviderConfigBuilderV2;
use Tmilos\ScimSchema\Model\SPC\AuthenticationScheme;

class ServiceProviderConfigBuilderV2Test extends \PHPUnit_Framework_TestCase
{
    public function test_builds_default_service_provider_config()
    {
        $builder = new ServiceProviderConfigBuilderV2();
        $spc = $builder->buildServiceProviderConfig();
        $arr = $spc->serializeObject();

        $this->assertEquals(TestHelper::getExpected('v2.service_provider_config.default.json'), $arr);
    }

    public function test_builds_custom_service_provider_config()
    {
        $builder = new ServiceProviderConfigBuilderV2();
        $builder
            ->setDocumentationUri('http://localhost/doc.html')
            ->setPatchSupported(true)
            ->setBulkSupported(true)
            ->setBulkMaxOperations(1000)
            ->setBulkMaxPayloadSize(1048576)
            ->setFilterSupported(true)
            ->setFilterMaxResults(200)
            ->setETagSupported(true)
            ->setChangePasswordSupported(true)
            ->setSortSupported(true)
            ->addAuthenticationScheme(new AuthenticationScheme(
                AuthenticationScheme::OAUTH_BEARER_TOKEN,
                'OAuth Bearer Token',
                'Authentication scheme using the OAuth Bearer Token Standard',
                'http://www.rfc-editor.org/info/rfc6750',
                'http://localhost/oauthbearertoken.html'
            ))
        ;

        $spc = $builder->buildServiceProviderConfig();
        $arr = $spc->serializeObject();

        $this->assertEquals(TestHelper::getExpected('v2.service_provider_config.custom.json'), $arr);
    }
}
