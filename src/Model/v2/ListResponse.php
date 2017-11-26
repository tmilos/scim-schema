<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Model\v2;

use Tmilos\ScimSchema\ScimConstantsV2;

class ListResponse extends \Tmilos\ScimSchema\Model\ListResponse
{
    public function getSchemaId()
    {
        return ScimConstantsV2::MESSAGE_LIST_RESPONSE;
    }
}
