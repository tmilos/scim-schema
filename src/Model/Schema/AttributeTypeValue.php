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

abstract class AttributeTypeValue
{
    const STRING = 'string';
    const BOOLEAN = 'boolean';
    const DECIMAL = 'decimal';
    const INTEGER = 'integer';
    const DATETIME = 'dateTime';
    const BINARY = 'binary';
    const REFERENCE = 'reference';
    const COMPLEX = 'complex';

    /**
     * @param mixed  $value
     * @param string $type
     *
     * @return bool
     */
    public static function isValueValid($value, $type)
    {
        switch ($type) {
            case self::STRING: return is_string($value);
            case self::BOOLEAN: return is_bool($value);
            case self::DECIMAL: return is_float($value) || is_int($value);
            case self::INTEGER: return is_int($value);
            case self::DATETIME: return $value instanceof \DateTime;  // improve this
            case self::BINARY: return true;
            case self::REFERENCE: return is_string($value); // improve this
            case self::COMPLEX: return is_array($value) || is_object($value);
            default: throw new \LogicException('Unrecognized attribute type: '.$type);
        }
    }
}
