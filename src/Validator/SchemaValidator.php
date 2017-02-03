<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Validator;

use Tmilos\ScimSchema\Helper;
use Tmilos\ScimSchema\Model\Schema\Attribute;
use Tmilos\ScimSchema\Model\Schema;
use Tmilos\ScimSchema\ScimConstants;

class SchemaValidator
{
    private static $commonAttributes = [
        'id' => 1,
        'schemas' => 1,
        'externalId' => 1,
        'meta' => 1,
    ];

    /**
     * @param array    $object
     * @param Schema   $schema
     * @param Schema[] $schemaExtensions
     *
     * @return ValidationResult
     */
    public function validate(array $object, Schema $schema, array $schemaExtensions = [])
    {
        $validationResult = new ValidationResult();
        /** @var Schema[] $otherSchemas */
        $otherSchemas = [];
        foreach ($schemaExtensions as $schemaExtension) {
            $otherSchemas[$schemaExtension->getId()] = $schemaExtension;
        }

        $this->validateByAttributes($object, $schema->getId(), $schema->getAttributes(), $otherSchemas, $validationResult, null);

        foreach ($otherSchemas as $schemaId => $otherSchema) {
            if (isset($object[$schemaId])) {
                $this->validateByAttributes($object[$schemaId], $otherSchema->getId(), $otherSchema->getAttributes(), [], $validationResult, null);
            }
        }

        return $validationResult;
    }

    /**
     * @param array            $object
     * @param string           $schemaId
     * @param Attribute[]      $attributes
     * @param array            $ignoreAttributes
     * @param ValidationResult $validationResult
     * @param string           $parentPath
     */
    private function validateByAttributes(array $object, $schemaId, $attributes, array $ignoreAttributes, ValidationResult $validationResult, $parentPath)
    {
        foreach ($object as $propertyName => $value) {
            if (!$parentPath && isset(self::$commonAttributes[$propertyName])) {
                // skip common resource attributes
                continue;
            }
            if (!$parentPath && isset($ignoreAttributes[$propertyName])) {
                continue;
            }

            $attribute = Helper::findAttribute($propertyName, $attributes);
            if (!$attribute) {
                $validationResult->add($propertyName, $parentPath, $schemaId, 'Attribute is not defined');
                continue;
            }

            if ($this->isArray($value)) {
                if (!$attribute->isMultiValued()) {
                    $validationResult->add($propertyName, $parentPath, $schemaId, 'Attribute is not defined in schema as multi-valued, but got array');
                    continue;
                } else {
                    foreach ($value as $item) {
                        $this->validateByAttributes($item, $schemaId, $attribute->getSubAttributes(), [], $validationResult, $propertyName);
                    }
                }
            } elseif ($this->isObject($value)) {
                if ($attribute->isMultiValued()) {
                    $validationResult->add($propertyName, $parentPath, $schemaId, 'Attribute is defined in schema as multi-valued, but got object');
                    continue;
                } elseif ($attribute->getType() !== ScimConstants::ATTRIBUTE_TYPE_COMPLEX) {
                    $validationResult->add($propertyName, $parentPath, $schemaId, 'Attribute is not defined in schema as complex, but got object');
                    continue;
                }
                $this->validateByAttributes($value, $schemaId, $attribute->getSubAttributes(), [], $validationResult, $propertyName);
            } else {
                if ($attribute->isMultiValued()) {
                    $validationResult->add($propertyName, $parentPath, $schemaId, 'Attribute is defined in schema as multi-valued, but got scalar');
                    continue;
                } elseif ($attribute->getType() === ScimConstants::ATTRIBUTE_TYPE_COMPLEX) {
                    $validationResult->add($propertyName, $parentPath, $schemaId, 'Attribute is defined in schema as complex, but got scalar');
                    continue;
                } elseif (!$attribute->isValueValid($value)) {
                    $validationResult->add($propertyName, $parentPath, $schemaId, sprintf('Attribute has invalid value for type "%s"', $attribute->getType()));
                    continue;
                }
            }
        }
    }

    /**
     * @param $value
     *
     * @return bool
     */
    private function isArray($value)
    {
        return is_array($value) && Helper::hasAllIntKeys($value);
    }

    /**
     * @param $value
     *
     * @return bool
     */
    private function isObject($value)
    {
        return is_array($value) && Helper::hasAllStringKeys($value);
    }
}
