<?php

namespace Tmilos\ScimSchema\Model\v2;

use Tmilos\ScimSchema\ScimConstantsV2;

class ListResponse extends \Tmilos\ScimSchema\Model\ListResponse
{
    public function getSchemaId()
    {
        return ScimConstantsV2::MESSAGE_LIST_RESPONSE;
    }
}
