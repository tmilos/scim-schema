<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Model\Schema;

use Tmilos\ScimSchema\Model\SerializableInterface;
use Tmilos\ScimSchema\ScimConstants;

class Attribute implements SerializableInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $type;

    /** @var bool */
    protected $multiValued = false;

    /** @var bool */
    protected $required = false;

    /** @var string */
    protected $description;

    /** @var Attribute[] */
    protected $subAttributes = [];

    /** @var string[] */
    protected $canonicalValues = [];

    /** @var bool */
    protected $caseExact = false;

    /** @var string */
    protected $mutability = ScimConstants::MUTABILITY_READ_WRITE;

    /** @var string */
    protected $returned = ScimConstants::RETURNED_ALWAYS;

    /** @var string */
    protected $uniqueness = ScimConstants::UNIQUENESS_NONE;

    /** @var string[] */
    protected $referenceTypes = [];

    /**
     * @param string      $name
     * @param string      $type
     * @param bool        $multiValued
     * @param bool        $required
     * @param string      $description
     * @param Attribute[] $subAttributes
     * @param \string[]   $canonicalValues
     * @param bool        $caseExact
     * @param string      $mutability
     * @param string      $returned
     * @param string      $uniqueness
     * @param \string[]   $referenceTypes
     */
    public function __construct(
        $name,
        $type,
        $multiValued,
        $required,
        $description,
        array $subAttributes,
        array $canonicalValues,
        $caseExact,
        $mutability,
        $returned,
        $uniqueness,
        array $referenceTypes
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->multiValued = $multiValued;
        $this->required = $required;
        $this->description = $description;
        $this->subAttributes = $subAttributes;
        $this->canonicalValues = $canonicalValues;
        $this->caseExact = $caseExact;
        $this->mutability = $mutability;
        $this->returned = $returned;
        $this->uniqueness = $uniqueness;
        $this->referenceTypes = $referenceTypes === null ? [] : $referenceTypes;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isMultiValued()
    {
        return $this->multiValued;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return Attribute[]
     */
    public function getSubAttributes()
    {
        return $this->subAttributes;
    }

    /**
     * @return \string[]
     */
    public function getCanonicalValues()
    {
        return $this->canonicalValues;
    }

    /**
     * @return bool
     */
    public function isCaseExact()
    {
        return $this->caseExact;
    }

    /**
     * @return string
     */
    public function getMutability()
    {
        return $this->mutability;
    }

    /**
     * @return string
     */
    public function getReturned()
    {
        return $this->returned;
    }

    /**
     * @return string
     */
    public function getUniqueness()
    {
        return $this->uniqueness;
    }

    /**
     * @return \string[]
     */
    public function getReferenceTypes()
    {
        return $this->referenceTypes;
    }

    /**
     * @param string $name
     *
     * @return null|Attribute
     */
    public function findAttribute($name)
    {
        foreach ($this->subAttributes as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }

        return null;
    }

    public function isValueValid($value)
    {
        switch ($this->type) {
            case ScimConstants::ATTRIBUTE_TYPE_STRING: return is_string($value);
            case ScimConstants::ATTRIBUTE_TYPE_BOOLEAN: return is_bool($value);
            case ScimConstants::ATTRIBUTE_TYPE_DECIMAL: return is_float($value) || is_int($value);
            case ScimConstants::ATTRIBUTE_TYPE_INTEGER: return is_int($value);
            case ScimConstants::ATTRIBUTE_TYPE_DATETIME: return $value instanceof \DateTime;  // improve this
            case ScimConstants::ATTRIBUTE_TYPE_BINARY: return true;
            case ScimConstants::ATTRIBUTE_TYPE_REFERENCE: return is_string($value); // improve this
            case ScimConstants::ATTRIBUTE_TYPE_COMPLEX: return is_array($value) || is_object($value);
            default: throw new \LogicException('Unrecognized attribute type: '.$this->type);
        }
    }

    /**
     * @return array
     */
    public function serializeObject()
    {
        $result = [
            'name' => $this->name,
        ];
        if ($this->description) {
            $result['description'] = $this->description;
        }
        $result['type'] = $this->type;
        $result['mutability'] = $this->mutability;
        $result['returned'] = $this->returned;

        if ($this->type !== ScimConstants::ATTRIBUTE_TYPE_BOOLEAN) {
            $result['uniqueness'] = $this->uniqueness;
        }

        $result['required'] = $this->required;
        $result['multiValued'] = $this->multiValued;
        $result['caseExact'] = $this->caseExact;

        if ($this->subAttributes) {
            $result['subAttributes'] = [];
            foreach ($this->subAttributes as $subAttribute) {
                $result['subAttributes'][] = $subAttribute->serializeObject();
            }
        }
        if ($this->canonicalValues) {
            $result['canonicalValues'] = $this->canonicalValues;
        }
        if ($this->referenceTypes) {
            $result['referenceTypes'] = $this->referenceTypes;
        }

        return $result;
    }

    /**
     * @param array $data
     *
     * @return Attribute
     */
    public static function deserializeObject(array $data)
    {
        $subAttributes = [];
        if (isset($data['subAttributes'])) {
            foreach ($data['subAttributes'] as $subAttribute) {
                $subAttributes[] = static::deserializeObject($subAttribute);
            }
        }
        $result = new static(
            $data['name'],
            $data['type'],
            $data['multiValued'],
            $data['required'],
            isset($data['description']) ? $data['description'] : null,
            $subAttributes,
            isset($data['canonicalValues']) ? $data['canonicalValues'] : [],
            isset($data['caseExact']) ? $data['caseExact'] : false,
            $data['mutability'],
            $data['returned'],
            isset($data['uniqueness']) ? $data['uniqueness'] : null,
            isset($data['referenceTypes']) ? $data['referenceTypes'] : []
        );

        return $result;
    }
}
