<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Builder;

use Tmilos\ScimSchema\Model\Schema\Attribute;
use Tmilos\ScimSchema\ScimConstants;

class AttributeBuilder
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
    protected $mutability;

    /** @var string */
    protected $returned;

    /** @var string */
    protected $uniqueness;

    /** @var string[] */
    protected $referenceTypes = [];

    /**
     * @param string $name
     * @param string $type
     * @param string $description
     *
     * @return AttributeBuilder
     */
    public static function create($name, $type, $description = null)
    {
        $result = new static();
        $result->name = $name;
        $result->type = $type;
        $result->description = $description;

        return $result;
    }

    protected function __construct()
    {
        $this->mutability = ScimConstants::MUTABILITY_READ_WRITE;
        $this->returned = ScimConstants::RETURNED_DEFAULT;
        $this->uniqueness = ScimConstants::UNIQUENESS_NONE;
    }

    /**
     * @return Attribute
     */
    public function getAttribute()
    {
        return new Attribute($this->name, $this->type, $this->multiValued, $this->required, $this->description, $this->subAttributes,
            $this->canonicalValues, $this->caseExact, $this->mutability, $this->returned, $this->uniqueness, $this->referenceTypes);
    }

    /**
     * @param bool $multiValued
     *
     * @return AttributeBuilder
     */
    public function setMultiValued($multiValued)
    {
        $this->multiValued = (bool) $multiValued;

        return $this;
    }

    /**
     * @param bool $required
     *
     * @return AttributeBuilder
     */
    public function setRequired($required)
    {
        $this->required = (bool) $required;

        return $this;
    }

    /**
     * @param string $description
     *
     * @return AttributeBuilder
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;

        return $this;
    }

    /**
     * @param bool $caseExact
     *
     * @return AttributeBuilder
     */
    public function setCaseExact($caseExact)
    {
        $this->caseExact = (bool) $caseExact;

        return $this;
    }

    /**
     * @param string $mutability
     *
     * @return AttributeBuilder
     */
    public function setMutability($mutability)
    {
        $this->mutability = $mutability;

        return $this;
    }

    /**
     * @param string $returned
     *
     * @return AttributeBuilder
     */
    public function setReturned($returned)
    {
        $this->returned = $returned;

        return $this;
    }

    /**
     * @param string $uniqueness
     *
     * @return AttributeBuilder
     */
    public function setUniqueness($uniqueness)
    {
        $this->uniqueness = $uniqueness;

        return $this;
    }

    /**
     * @param Attribute $attribute
     *
     * @return AttributeBuilder
     */
    public function addSubAttribute(Attribute $attribute)
    {
        $this->subAttributes[] = $attribute;

        return $this;
    }

    /**
     * @param Attribute[] $attributes
     *
     * @return AttributeBuilder
     */
    public function addSubAttributes(array $attributes)
    {
        foreach ($attributes as $attribute) {
            $this->addSubAttribute($attribute);
        }

        return $this;
    }

    /**
     * @param \string[] $canonicalValues
     *
     * @return AttributeBuilder
     */
    public function setCanonicalValues(array $canonicalValues)
    {
        $this->canonicalValues = $canonicalValues;

        return $this;
    }

    /**
     * @param \string[] $referenceTypes
     *
     * @return AttributeBuilder
     */
    public function setReferenceTypes(array $referenceTypes)
    {
        $this->referenceTypes = [];
        foreach ($referenceTypes as $referenceType) {
            $this->addReferenceType($referenceType);
        }

        return $this;
    }

    /**
     * @param string $referenceType
     *
     * @return AttributeBuilder
     */
    public function addReferenceType($referenceType)
    {
        $this->referenceTypes[] = $referenceType;

        return $this;
    }

    /**
     * @param string $canonicalValue
     *
     * @return AttributeBuilder
     */
    public function addCanonicalValue($canonicalValue)
    {
        $this->canonicalValues[] = (string) $canonicalValue;

        return $this;
    }
}
