<?php

namespace Tests\Tmilos\ScimSchema\Builder;

use Tmilos\ScimSchema\Builder\ResourceTypeBuilder;
use Tmilos\ScimSchema\ScimConstants;

class ResourceTypeBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function test_group()
    {
        $builder = new ResourceTypeBuilder();
        $resourceTypes = [];
        $resourceTypes[] = $builder->build(ScimConstants::RESOURCE_TYPE_GROUP)->serializeObject();
        $resourceTypes[] = $builder->build(ScimConstants::RESOURCE_TYPE_SERVICE_PROVIDER_CONFIG)->serializeObject();
        $resourceTypes[] = $builder->build(ScimConstants::RESOURCE_TYPE_USER)->serializeObject();
        $resourceTypes[] = $builder->build(ScimConstants::RESOURCE_TYPE_SCHEMA)->serializeObject();
        $resourceTypes[] = $builder->build(ScimConstants::RESOURCE_TYPE_RESOURCE_TYPE)->serializeObject();

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
