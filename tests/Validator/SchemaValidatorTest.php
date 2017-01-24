<?php

namespace Tmilos\ScimSchema\Validator;

use Tmilos\ScimSchema\Builder\AttributeBuilder;
use Tmilos\ScimSchema\Builder\SchemaBuilder;
use Tmilos\ScimSchema\Model\AttributeTypeValue;
use Tmilos\ScimSchema\Model\Schema;

class SchemaValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var SchemaValidator */
    private $validator;

    /** @var SchemaBuilder */
    private $schemaBuilder;

    protected function setUp()
    {
        $this->validator = new SchemaValidator();
        $this->schemaBuilder = new SchemaBuilder();
    }

    public function test_minimal_user_representation()
    {
        $result = $this->validator->validate(
            $this->loadFromFile(__DIR__.'/user.ok.minimal.json'),
            $this->schemaBuilder->getUser()
        );

        $this->assertEquals([], $result->getErrorsAsStrings());
    }

    public function test_full_user_representation()
    {
        $result = $this->validator->validate(
            $this->loadFromFile(__DIR__.'/user.ok.full.json'),
            $this->schemaBuilder->getUser()
        );

        $this->assertEquals([], $result->getErrorsAsStrings());
    }

    public function test_enterprise_user_full()
    {
        $result = $this->validator->validate(
            $this->loadFromFile(__DIR__.'/enterprise_user.ok.full.json'),
            $this->schemaBuilder->getUser(),
            [$this->schemaBuilder->getEnterpriseUser()]
        );

        $this->assertEquals([], $result->getErrorsAsStrings(), implode("\n", $result->getErrorsAsStrings()));
    }

    public function test_error_on_undefined_attribute()
    {
        $schema = new Schema(Schema::USER);
        $schema->getAttributes()->add(AttributeBuilder::create('foo', AttributeTypeValue::STRING()));

        $object = [
            'bar' => '123',
        ];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[bar] Attribute is not defined [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_array_for_single_valued_attribute()
    {
        $schema = new Schema(Schema::USER);
        $schema->getAttributes()->add(AttributeBuilder::create('foo', AttributeTypeValue::STRING()));

        $object = [
            'foo' => [1,2,3],
        ];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[foo] Attribute is not defined in schema as multi-valued, but got array [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_object_for_multivalued_attribute()
    {
        $schema = new Schema(Schema::USER);
        $schema->getAttributes()->add(
            AttributeBuilder::create('name', AttributeTypeValue::STRING())
                ->setMultiValued(true)
        );

        $object = [
            'name' => [
                'a' => 1,
                'b' => 2,
            ],
        ];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[name] Attribute is defined in schema as multi-valued, but got object [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_object_for_scalar_attribute()
    {
        $schema = new Schema(Schema::USER);
        $schema->getAttributes()->add(
            AttributeBuilder::create('name', AttributeTypeValue::STRING())
        );

        $object = [
            'name' => [
                'a' => 1,
                'b' => 2,
            ],
        ];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[name] Attribute is not defined in schema as complex, but got object [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_scalar_for_multivalued_attribute()
    {
        $schema = new Schema(Schema::USER);
        $schema->getAttributes()->add(
            AttributeBuilder::create('name', AttributeTypeValue::STRING())
                ->setMultiValued(true)
        );

        $object = [
            'name' => 'john',
        ];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[name] Attribute is defined in schema as multi-valued, but got scalar [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_scalar_for_complex_attribute()
    {
        $schema = new Schema(Schema::USER);
        $schema->getAttributes()->add(
            AttributeBuilder::create('name', AttributeTypeValue::COMPLEX())
        );

        $object = ['name' => 'john'];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[name] Attribute is defined in schema as complex, but got scalar [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_invalid_string_type()
    {
        $schema = new Schema(Schema::USER);
        $schema->getAttributes()->add(
            AttributeBuilder::create('name', AttributeTypeValue::STRING())
        );

        $object = ['name' => 123];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[name] Attribute has invalid value for type "string" [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_invalid_bool_type()
    {
        $schema = new Schema(Schema::USER);
        $schema->getAttributes()->add(
            AttributeBuilder::create('name', AttributeTypeValue::BOOLEAN())
        );

        $object = ['name' => 'john'];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[name] Attribute has invalid value for type "boolean" [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_invalid_attribute_in_schema_extension()
    {
        $schema = new Schema(Schema::USER);
        $schema->getAttributes()->add(
            AttributeBuilder::create('name', AttributeTypeValue::STRING())
        );
        $extensionSchema = new Schema(Schema::ENTERPRISE_USER);
        $extensionSchema->getAttributes()->add(
            AttributeBuilder::create('extra', AttributeTypeValue::BOOLEAN())
        );

        $object = [
            'name' => 'john',
            Schema::ENTERPRISE_USER => [
                'extra' => true,
                'not_defined_property' => 123,
            ],
        ];

        $result = $this->validator->validate($object, $schema, [$extensionSchema]);

        $this->assertEquals([
            '[not_defined_property] Attribute is not defined [urn:ietf:params:scim:schemas:extension:enterprise:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_invalid_sub_attribute_in_main_schema()
    {
        $schema = new Schema(Schema::USER);
        $schema->getAttributes()->add(
            AttributeBuilder::create('foo', AttributeTypeValue::COMPLEX())
                ->addSubAttribute(
                    AttributeBuilder::create('bar', AttributeTypeValue::STRING())->getAttribute()
                )
        );

        $object = [
            'foo' => [
                'other' => 'aaaa',
            ],
        ];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[foo.other] Attribute is not defined [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    private function loadFromFile($filename)
    {
        return json_decode(file_get_contents($filename), true);
    }
}
