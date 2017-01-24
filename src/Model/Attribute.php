<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Model;

class Attribute
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
    protected $mutability = MutabilityValue::READ_WRITE;

    /** @var string */
    protected $returned = ReturnedValue::ALWAYS;

    /** @var string */
    protected $uniqueness = UniquenessValue::NONE;

    /** @var string[] */
    protected $referenceTypes = [];

    // INTERMEDIARY VALUE OBJECTS -----------

    /** @var AttributeTypeValue */
    private $typeValue;

    /** @var MutabilityValue */
    private $mutabilityValue;

    /** @var ReturnedValue */
    private $returnedValue;

    /** @var UniquenessValue */
    private $uniquenessValue;

    /**
     * @param string             $name
     * @param AttributeTypeValue $type
     * @param bool               $multiValued
     * @param bool               $required
     * @param string             $description
     * @param Attribute[]        $subAttributes
     * @param \string[]          $canonicalValues
     * @param bool               $caseExact
     * @param MutabilityValue    $mutability
     * @param ReturnedValue      $returned
     * @param UniquenessValue    $uniqueness
     * @param \string[]          $referenceTypes
     */
    public function __construct(
        $name,
        AttributeTypeValue $type,
        $multiValued,
        $required,
        $description,
        array $subAttributes,
        array $canonicalValues,
        $caseExact,
        MutabilityValue $mutability,
        ReturnedValue $returned,
        UniquenessValue $uniqueness,
        array $referenceTypes
    ) {
        $this->name = $name;
        $this->type = $type->getValue();
        $this->typeValue = $type;
        $this->multiValued = $multiValued;
        $this->required = $required;
        $this->description = $description;
        $this->subAttributes = $subAttributes;
        $this->canonicalValues = $canonicalValues;
        $this->caseExact = $caseExact;
        $this->mutability = $mutability->getValue();
        $this->mutabilityValue = $mutability;
        $this->returned = $returned->getValue();
        $this->returnedValue = $returned;
        $this->uniqueness = $uniqueness->getValue();
        $this->uniquenessValue = $uniqueness;
        $this->referenceTypes = $referenceTypes;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return AttributeTypeValue
     */
    public function getType()
    {
        if (!$this->typeValue) {
            $this->typeValue = new AttributeTypeValue($this->type);
        }

        return $this->typeValue;
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
     * @return MutabilityValue
     */
    public function getMutability()
    {
        if (!$this->mutabilityValue) {
            $this->mutabilityValue = new MutabilityValue($this->mutability);
        }

        return $this->mutabilityValue;
    }

    /**
     * @return ReturnedValue
     */
    public function getReturned()
    {
        if (!$this->returnedValue) {
            $this->returnedValue = new ReturnedValue($this->returned ?: ReturnedValue::BY_DEFAULT);
        }

        return $this->returnedValue;
    }

    /**
     * @return UniquenessValue
     */
    public function getUniqueness()
    {
        if (!$this->uniquenessValue) {
            $this->uniquenessValue = new UniquenessValue($this->uniqueness ?: UniquenessValue::NONE);
        }

        return $this->uniquenessValue;
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

    /**
     * @return array
     */
    public function toArray()
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

        if ($this->type !== AttributeTypeValue::BOOLEAN) {
            $result['uniqueness'] = $this->uniqueness;
        }

        $result['required'] = $this->required;
        $result['multiValued'] = $this->multiValued;
        $result['caseExact'] = $this->caseExact;

        if ($this->subAttributes) {
            $result['subAttributes'] = [];
            foreach ($this->subAttributes as $subAttribute) {
                $result['subAttributes'][] = $subAttribute->toArray();
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
}
