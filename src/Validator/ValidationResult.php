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

class ValidationResult
{
    /** @var ValidationError[] */
    private $errors = [];

    /**
     * @param ValidationResult $validationResult
     */
    public function mergeWith(ValidationResult $validationResult)
    {
        foreach ($validationResult->getErrors() as $error) {
            $this->errors[] = $error;
        }
    }

    /**
     * @param string $attributeName
     * @param string $parentAttribute
     * @param string $schemaId
     * @param string $errorMessage
     */
    public function add($attributeName, $parentAttribute, $schemaId, $errorMessage)
    {
        $this->addError(new ValidationError($attributeName, $parentAttribute, $schemaId, $errorMessage));
    }

    /**
     * @param ValidationError $error
     */
    public function addError(ValidationError $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @return ValidationError[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return \string[]
     */
    public function getErrorsAsStrings()
    {
        return array_map(function (ValidationError $error) {
            return (string) $error;
        }, $this->errors);
    }
}
