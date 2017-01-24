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

use Doctrine\Common\Collections\ArrayCollection;
use Tmilos\ScimSchema\Builder\AttributeBuilder;

class AttributeCollection extends ArrayCollection
{
    /**
     * @param string      $name
     * @param Attribute[] $attributes
     *
     * @return Attribute|null
     */
    public static function find($name, $attributes)
    {
        foreach ($attributes as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }

        return null;
    }

    public function add($value)
    {
        if ($value instanceof AttributeBuilder) {
            $value = $value->getAttribute();
        }
        if (!$value instanceof Attribute) {
            $type = gettype($value);
            if ($type === 'object') {
                $type = get_class($value);
            }
            throw new \InvalidArgumentException('Expected Attribute instance and got '.$type);
        }

        return parent::add($value);
    }
}
