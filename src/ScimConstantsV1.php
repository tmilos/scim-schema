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

abstract class ScimConstantsV1
{
    const CORE = 'urn:scim:schemas:core:1.0';
    const SCHEMA_USER = 'urn:scim:schemas:core:1.0:User';
    const SCHEMA_ENTERPRISE_USER = 'urn:scim:schemas:extension:enterprise:1.0';
    const SCHEMA_GROUP = 'urn:scim:schemas:core:1.0:Group';
    const SCHEMA_SERVICE_PROVIDER_CONFIG = 'urn:scim:schemas:core:1.0:Service Provider Configuration';
    const SCHEMA_SCHEMA = 'urn:scim:schemas:core:1.0:Schema';

    const MESSAGE_LIST_RESPONSE = 'urn:scim:schemas:core:1.0';
    const MESSAGE_SEARCH_REQUEST = 'urn:ietf:params:scim:api:messages:2.0:SearchRequest';

    const ENDPOINT_USERS = 'v1/users';
    const ENDPOINT_GROUPS = 'v1/groups';
    const ENDPOINT_SERVICE_PROVIDER_CONFIG = 'v1/serviceproviderconfig';
    const ENDPOINT_SCHEMAS = 'v1/schemas';
}
