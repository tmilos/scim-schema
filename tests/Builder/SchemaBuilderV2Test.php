<?php

namespace Tests\Tmilos\ScimSchema\Builder;

use Tmilos\ScimSchema\Builder\SchemaBuilderV2;

class SchemaBuilderV2Test extends \PHPUnit_Framework_TestCase
{
    public function test_group()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getGroup()->serializeObject();

        $this->compare($this->getExpectedGroup(), $schema);
    }

    public function test_user()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getUser()->serializeObject();

        $this->compare($this->getExpectedUser(), $schema);
    }

    public function test_schema()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getSchema()->serializeObject();

        $this->compare($this->getExpectedSchema(), $schema);
    }

    public function test_resource_type()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getResourceType()->serializeObject();

        $this->compare($this->getExpectedResourceType(), $schema);
    }

    public function test_service_provider_config()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getServiceProviderConfig()->serializeObject();

        $this->compare($this->getExpectedServiceProviderConfig(), $schema);
    }

    public function test_enterprise_user()
    {
        $builder = new SchemaBuilderV2();
        $schema = $builder->getEnterpriseUser()->serializeObject();

        $this->compare($this->getExpectedEnterpriseUser(), $schema);
    }

    private function compare($expected, $actual)
    {
        $expectedArr = explode("\n", str_replace("\r", '', json_encode($expected, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)));
        $actualArr = explode("\n", str_replace("\r", '', json_encode($actual, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)));

        $minSize = min(count($expectedArr), count($actualArr));
        for ($i=0; $i<$minSize; $i++) {
            if ($expectedArr[$i] != $actualArr[$i]) {
                $expectedArr[$i] = '!'.$expectedArr[$i];
                $actualArr[$i] = '!'.$actualArr[$i];
                print "\nMismatch at line $i:\n";
                $start = $i - 10;
                if ($start < 0) { $start = 0; }
                $expectedBuffer = implode("\n", array_slice($expectedArr, $start, 20));
                $actualBuffer = implode("\n", array_slice($actualArr, $start, 20));

                print "Expected:\n$expectedBuffer\n\nbut got:\n$actualBuffer\n";

                $this->fail();
            }
        }

        $this->assertEquals($expected, $actual);
    }

    private function getExpected($filename)
    {
        $json = trim(file_get_contents(__DIR__.'/'.$filename));
        $result = json_decode($json, true);
        if (!$result) {
            $this->fail("Error json decoding file '$filename': ".json_last_error_msg());
        }

        return $result;
    }

    /**
     * @return \stdClass
     */
    private function getExpectedGroup()
    {
        return $this->getExpected('schema.group.json');
    }

    /**
     * @return \stdClass
     */
    private function getExpectedUser()
    {
        return $this->getExpected('schema.user.json');
    }

    /**
     * @return \stdClass
     */
    private function getExpectedSchema()
    {
        return $this->getExpected('schema.schema.json');
    }

    /**
     * @return \stdClass
     */
    private function getExpectedResourceType()
    {
        return $this->getExpected('schema.resource_type.json');
    }

    /**
     * @return \stdClass
     */
    private function getExpectedServiceProviderConfig()
    {
        return $this->getExpected('schema.service_provider_config.json');
    }

    /**
     * @return \stdClass
     */
    private function getExpectedEnterpriseUser()
    {
        return $this->getExpected('schema.enterprise_user.json');
    }
}
