<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema;

use Tmilos\ScimSchema\Model\Schema\Attribute;

abstract class Helper
{
    /**
     * @param string      $name
     * @param Attribute[] $attributes
     *
     * @return Attribute|null
     */
    public static function findAttribute($name, $attributes)
    {
        foreach ($attributes as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }

        return null;
    }

    public static function dateTime2string(\DateTime $dateTime)
    {
        if ($dateTime->getTimezone()->getName() === \DateTimeZone::UTC) {
            return $dateTime->format('Y-m-d\TH:i:s\Z');
        } else {
            return $dateTime->format('Y-m-d\TH:i:sP'); // ???
        }
    }

    /**
     * @param string        $string
     * @param \DateTimeZone $zone
     *
     * @return \DateTime
     */
    public static function string2dateTime($string, \DateTimeZone $zone = null)
    {
        if (!$zone) {
            $zone = new \DateTimeZone('UTC');
        }

        $dt = new \DateTime('now', $zone);
        $dt->setTimestamp(self::string2timestamp($string));

        return $dt;
    }

    /**
     * @param $string
     *
     * @return int
     */
    public static function string2timestamp($string)
    {
        $matches = array();
        if (!preg_match(
                '/^(\\d\\d\\d\\d)-(\\d\\d)-(\\d\\d)T(\\d\\d):(\\d\\d):(\\d\\d)(?:\\.\\d+)?Z$/D',
                $string,
                $matches
            )
        ) {
            throw new \InvalidArgumentException('Invalid timestamp: '.$string);
        }

        $year = intval($matches[1]);
        $month = intval($matches[2]);
        $day = intval($matches[3]);
        $hour = intval($matches[4]);
        $minute = intval($matches[5]);
        $second = intval($matches[6]);
        // Use gmmktime because the timestamp will always be given in UTC?
        $ts = gmmktime($hour, $minute, $second, $month, $day, $year);

        return $ts;
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    public static function hasAllStringKeys(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) === count($array);
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    public static function hasAllIntKeys(array $array)
    {
        return count(array_filter(array_keys($array), 'is_int')) === count($array);
    }
}
