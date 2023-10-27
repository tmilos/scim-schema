<?php

namespace Tests\Tmilos\ScimSchema\Builder;

use PHPUnit\Framework\TestCase;
use Tests\Tmilos\ScimSchema\TestHelper;
use Tmilos\ScimSchema\Builder\SchemaBuilderV1;

class SchemaBuilderV1Test extends TestCase
{
    public function test_group()
    {
        $builder = new SchemaBuilderV1();
        $schema = $builder->getGroup()->serializeObject();

        TestHelper::compare(TestHelper::getExpected('v1.schema.group.json'), $schema, $this);
    }
}
