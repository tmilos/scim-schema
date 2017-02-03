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

abstract class ScimConstantsV2
{
    const SCHEMA_USER = 'urn:ietf:params:scim:schemas:core:2.0:User';
    const SCHEMA_ENTERPRISE_USER = 'urn:ietf:params:scim:schemas:extension:enterprise:2.0:User';
    const SCHEMA_GROUP = 'urn:ietf:params:scim:schemas:core:2.0:Group';
    const SCHEMA_SERVICE_PROVIDER_CONFIG = 'urn:ietf:params:scim:schemas:core:2.0:ServiceProviderConfig';
    const SCHEMA_RESOURCE_TYPE = 'urn:ietf:params:scim:schemas:core:2.0:ResourceType';
    const SCHEMA_SCHEMA = 'urn:ietf:params:scim:schemas:core:2.0:Schema';

    const MESSAGE_BULK_REQUEST = 'urn:ietf:params:scim:api:messages:2.0:BulkRequest';
    const MESSAGE_BULK_RESPONSE = 'urn:ietf:params:scim:api:messages:2.0:BulkResponse';
    const MESSAGE_ERROR = 'urn:ietf:params:scim:api:messages:2.0:Error';
    const MESSAGE_PATCH_OP = 'urn:ietf:params:scim:api:messages:2.0:PatchOp';
    const MESSAGE_LIST_RESPONSE = 'urn:ietf:params:scim:api:messages:2.0:ListResponse';
    const MESSAGE_SEARCH_REQUEST = 'urn:ietf:params:scim:api:messages:2.0:SearchRequest';

    const ENDPOINT_USERS = 'v2/users';
    const ENDPOINT_GROUPS = 'v2/groups';
    const ENDPOINT_RESOURCE_TYPES = 'v2/resourcetypes';
    const ENDPOINT_SERVICE_PROVIDER_CONFIG = 'v2/serviceproviderconfig';
    const ENDPOINT_SCHEMAS = 'v2/schemas';
}
