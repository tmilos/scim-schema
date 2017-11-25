<?php

namespace Tests\Tmilos\ScimSchema\Builder;

use Tests\Tmilos\ScimSchema\TestHelper;
use Tmilos\ScimSchema\Builder\SchemaBuilderV1;

class SchemaBuilderV1Test extends \PHPUnit_Framework_TestCase
{
    public function test_group()
    {
        $builder = new SchemaBuilderV1();
        $schema = $builder->getGroup()->serializeObject();

        TestHelper::compare(TestHelper::getExpected('v1.schema.group.json'), $schema, $this);
    }
}
