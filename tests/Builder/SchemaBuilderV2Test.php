<?php

namespace Tests\Tmilos\ScimSchema\Builder;

use Tests\Tmilos\ScimSchema\TestHelper;
use Tmilos\ScimSchema\Builder\SchemaBuilderV2;

class SchemaBuilderV2Test extends \PHPUnit_Framework_TestCase
{
    public function test_group()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getGroup()->serializeObject();

        TestHelper::compare(TestHelper::getExpected('v2.schema.group.json'), $schema, $this);
    }

    public function test_user()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getUser()->serializeObject();

        TestHelper::compare(TestHelper::getExpected('v2.schema.user.json'), $schema, $this);
    }

    public function test_schema()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getSchema()->serializeObject();

        TestHelper::compare(TestHelper::getExpected('v2.schema.schema.json'), $schema, $this);
    }

    public function test_resource_type()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getResourceType()->serializeObject();

        TestHelper::compare(TestHelper::getExpected('v2.schema.resource_type.json'), $schema, $this);
    }

    public function test_service_provider_config()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getServiceProviderConfig()->serializeObject();

        TestHelper::compare(TestHelper::getExpected('v2.schema.service_provider_config.json'), $schema, $this);
    }

    public function test_enterprise_user()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getEnterpriseUser()->serializeObject();

        TestHelper::compare(TestHelper::getExpected('v2.schema.enterprise_user.json'), $schema, $this);
    }
}
