<?php

namespace Tmilos\ScimSchema\Builder;

use Tmilos\ScimSchema\Model\SPC\AuthenticationScheme;

class ServiceProviderConfigBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function test_builds_default_service_provider_config()
    {
        $builder = new ServiceProviderConfigBuilder();
        $spc = $builder->buildServiceProviderConfig();
        $arr = $spc->toArray();

        $this->assertEquals($this->getExpected(__DIR__.'/service_provider_config.default.json'), $arr);
    }

    public function test_builds_custom_service_provider_config()
    {
        $builder = new ServiceProviderConfigBuilder();
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
        $arr = $spc->toArray();

        $this->assertEquals($this->getExpected(__DIR__.'/service_provider_config.custom.json'), $arr);
    }

    /**
     * @param $filename
     *
     * @return array
     */
    private function getExpected($filename)
    {
        $json = trim(file_get_contents($filename));
        $result = json_decode($json, true);
        if (!$result) {
            $this->fail("Error json decoding file '$filename': ".json_last_error_msg());
        }

        return $result;
    }
}
