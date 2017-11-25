<?php

namespace Tmilos\ScimSchema\Model\v1;

use Tmilos\ScimSchema\ScimConstantsV1;

class ListResponse extends \Tmilos\ScimSchema\Model\ListResponse
{
    public function getSchemaId()
    {
        return ScimConstantsV1::MESSAGE_LIST_RESPONSE;
    }
}
