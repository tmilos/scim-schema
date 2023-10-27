<?php

namespace Tests\Tmilos\ScimSchema\Validator;

use PHPUnit\Framework\TestCase;
use Tmilos\ScimSchema\Builder\AttributeBuilder;
use Tmilos\ScimSchema\Builder\SchemaBuilderV2;
use Tmilos\ScimSchema\Model\v2\Schema;
use Tmilos\ScimSchema\ScimConstants;
use Tmilos\ScimSchema\ScimConstantsV2;
use Tmilos\ScimSchema\Validator\SchemaValidator;

class SchemaValidatorTest extends TestCase
{
    /** @var SchemaValidator */
    private $validator;

    /** @var SchemaBuilderV2 */
    private $schemaBuilder;

    protected function setUp(): void
    {
        $this->validator = new SchemaValidator();
        $this->schemaBuilder = new SchemaBuilderV2();
    }

    public function test_minimal_user_representation()
    {
        $result = $this->validator->validate(
            $this->loadFromFile('/v2.user.ok.minimal.json'),
            $this->schemaBuilder->getUser()
        );

        $this->assertEquals([], $result->getErrorsAsStrings());
    }

    public function test_full_user_representation()
    {
        $result = $this->validator->validate(
            $this->loadFromFile('/v2.user.ok.full.json'),
            $this->schemaBuilder->getUser()
        );

        $this->assertEquals([], $result->getErrorsAsStrings());
    }

    public function test_enterprise_user_full()
    {
        $result = $this->validator->validate(
            $this->loadFromFile('/v2.enterprise_user.ok.full.json'),
            $this->schemaBuilder->getUser(),
            [$this->schemaBuilder->getEnterpriseUser()]
        );

        $this->assertEquals([], $result->getErrorsAsStrings(), implode("\n", $result->getErrorsAsStrings()));
    }

    public function test_error_on_undefined_attribute()
    {
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('foo', ScimConstants::ATTRIBUTE_TYPE_STRING)->getAttribute()
        );

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
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('foo', ScimConstants::ATTRIBUTE_TYPE_STRING)->getAttribute()
        );

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
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('name', ScimConstants::ATTRIBUTE_TYPE_STRING)
                ->setMultiValued(true)
                ->getAttribute()
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
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('name', ScimConstants::ATTRIBUTE_TYPE_STRING)->getAttribute()
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
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('name', ScimConstants::ATTRIBUTE_TYPE_STRING)
                ->setMultiValued(true)
                ->getAttribute()
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
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('name', ScimConstants::ATTRIBUTE_TYPE_COMPLEX)->getAttribute()
        );

        $object = ['name' => 'john'];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[name] Attribute is defined in schema as complex, but got scalar [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_invalid_string_type()
    {
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('name', ScimConstants::ATTRIBUTE_TYPE_STRING)->getAttribute()
        );

        $object = ['name' => 123];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[name] Attribute has invalid value for type "string" [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_invalid_bool_type()
    {
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('name', ScimConstants::ATTRIBUTE_TYPE_BOOLEAN)->getAttribute()
        );

        $object = ['name' => 'john'];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[name] Attribute has invalid value for type "boolean" [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_error_on_invalid_datetime_type()
    {
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('birthDate', ScimConstants::ATTRIBUTE_TYPE_DATETIME)->getAttribute()
        );

        $object = ['birthDate' => '17/5-1814'];

        $result = $this->validator->validate($object, $schema);

        $this->assertEquals([
            '[birthDate] Attribute has invalid value for type "dateTime" [urn:ietf:params:scim:schemas:core:2.0:User]',
        ], $result->getErrorsAsStrings());
    }

    public function test_no_error_on_valid_datetime_type()
    {
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('birthDate', ScimConstants::ATTRIBUTE_TYPE_DATETIME)->getAttribute()
        );

        $object = ['birthDate' => '2019-01-31T17:19:37+01:00'];

        $result = $this->validator->validate($object, $schema);

        $this->assertEmpty($result->getErrors());
    }

    public function test_error_on_invalid_attribute_in_schema_extension()
    {
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('name', ScimConstants::ATTRIBUTE_TYPE_STRING)->getAttribute()
        );
        $extensionSchema = new Schema(ScimConstantsV2::SCHEMA_ENTERPRISE_USER);
        $extensionSchema->addAttribute(
            AttributeBuilder::create('extra', ScimConstants::ATTRIBUTE_TYPE_BOOLEAN)->getAttribute()
        );

        $object = [
            'name' => 'john',
            ScimConstantsV2::SCHEMA_ENTERPRISE_USER => [
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
        $schema = new Schema(ScimConstantsV2::SCHEMA_USER);
        $schema->addAttribute(
            AttributeBuilder::create('foo', ScimConstants::ATTRIBUTE_TYPE_COMPLEX)
                ->addSubAttribute(
                    AttributeBuilder::create('bar', ScimConstants::ATTRIBUTE_TYPE_STRING)->getAttribute()
                )
                ->getAttribute()
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

    /**
     * @param string $filename
     *
     * @return array
     */
    private function loadFromFile($filename)
    {
        return json_decode(file_get_contents(__DIR__.'/../resources/'.$filename), true);
    }
}
