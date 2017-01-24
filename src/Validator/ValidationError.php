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

class ValidationError
{
    /** @var string */
    private $attributeName;

    /** @var string */
    private $parentAttribute;

    /** @var string */
    private $schemaId;

    /** @var string */
    private $message;

    /**
     * @param string $attributeName
     * @param string $parentAttribute
     * @param string $schemaId
     * @param string $message
     */
    public function __construct($attributeName, $parentAttribute, $schemaId, $message)
    {
        $this->attributeName = $attributeName;
        $this->parentAttribute = $parentAttribute;
        $this->schemaId = $schemaId;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getAttributeName()
    {
        return $this->attributeName;
    }

    /**
     * @return string
     */
    public function getParentAttribute()
    {
        return $this->parentAttribute;
    }

    /**
     * @return string
     */
    public function getSchemaId()
    {
        return $this->schemaId;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function __toString()
    {
        return sprintf(
            '[%s%s] %s [%s]',
            $this->parentAttribute ? $this->parentAttribute.'.' : '',
            $this->attributeName,
            $this->message,
            $this->schemaId
        );
    }
}
