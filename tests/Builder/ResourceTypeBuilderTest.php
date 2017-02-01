<?php

namespace Tmilos\ScimSchema\Builder;

use Tmilos\ScimSchema\Model\ResourceType;

class ResourceTypeBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function test_group()
    {
        $builder = new ResourceTypeBuilder();
        $resourceTypes = [];
        $resourceTypes[] = $builder->build(ResourceType::GROUP)->serializeObject();
        $resourceTypes[] = $builder->build(ResourceType::SERVICE_PROVIDER_CONFIG)->serializeObject();
        $resourceTypes[] = $builder->build(ResourceType::USER)->serializeObject();
        $resourceTypes[] = $builder->build(ResourceType::SCHEMA)->serializeObject();
        $resourceTypes[] = $builder->build(ResourceType::RESOURCE_TYPE)->serializeObject();

        $this->assertEquals($this->getExpected(), $resourceTypes);
    }

    /**
     * @return \stdClass
     */
    private function getExpected()
    {
        $json = file_get_contents(__DIR__.'/resource_type.all.json');

        return json_decode($json, true);
    }
}
