<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Model\v1;

use Tmilos\ScimSchema\ScimConstantsV1;

class Schema extends \Tmilos\ScimSchema\Model\Schema
{
    public function getSchemaId()
    {
        return ScimConstantsV1::SCHEMA_SCHEMA;
    }

    public function serializeObject()
    {
        $parentValue = parent::serializeObject();

        $result = [
            'id' => $parentValue['id'],
            'schema' => ScimConstantsV1::CORE,
        ];

        unset($parentValue['id']);
        unset($parentValue['schemas']);

        foreach ($parentValue as $k=>$v) {
            $result[$k] = $v;
        }

        return $result;
    }
}
