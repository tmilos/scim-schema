<?php

namespace Tmilos\ScimSchema;

use Tmilos\ScimSchema\Model\Meta;

class MetaTest extends \PHPUnit_Framework_TestCase
{
    private $lastModified;

    public function test_can_change_values()
    {
        $meta = new Meta('aa', '', '', new \DateTime(), $this->lastModified);
        $meta->setLastModified($dt = new \DateTime());
        $this->assertSame($dt, $this->lastModified);
    }
}
