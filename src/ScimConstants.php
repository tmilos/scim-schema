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

abstract class ScimConstants
{
    const RESOURCE_TYPE_SCHEMA = 'Schema';
    const RESOURCE_TYPE_RESOURCE_TYPE = 'ResourceType';
    const RESOURCE_TYPE_SERVICE_PROVIDER_CONFIG = 'ServiceProviderConfig';
    const RESOURCE_TYPE_GROUP = 'Group';
    const RESOURCE_TYPE_USER = 'User';

    const ATTRIBUTE_TYPE_STRING = 'string';
    const ATTRIBUTE_TYPE_BOOLEAN = 'boolean';
    const ATTRIBUTE_TYPE_DECIMAL = 'decimal';
    const ATTRIBUTE_TYPE_INTEGER = 'integer';
    const ATTRIBUTE_TYPE_DATETIME = 'dateTime';
    const ATTRIBUTE_TYPE_BINARY = 'binary';
    const ATTRIBUTE_TYPE_REFERENCE = 'reference';
    const ATTRIBUTE_TYPE_COMPLEX = 'complex';

    /**
     * The attribute SHALL NOT be modified.
     */
    const MUTABILITY_READ_ONLY = 'readOnly';

    /**
     * The attribute MAY be updated and read at any time. This is the default value.
     */
    const MUTABILITY_READ_WRITE = 'readWrite';

    /**
     * The attribute MAY be defined at resource creation (e.g., POST) or at record replacement via a request (e.g., a PUT).
     * The attribute SHALL NOT be updated.
     */
    const MUTABILITY_IMMUTABLE = 'immutable';

    /**
     * The attribute MAY be updated at any time.  Attribute values SHALL NOT be returned (e.g., because the value is a stored hash).
     * Note: An attribute with a mutability of "writeOnly" usually also has a returned setting of "never".
     */
    const MUTABILITY_WRITE_ONLY = 'writeOnly';

    const REFERENCE_SCIM = 'scim';
    const REFERENCE_USER = 'User';
    const REFERENCE_GROUP = 'Group';
    const REFERENCE_EXTERNAL = 'external';
    const REFERENCE_URI = 'uri';

    /**
     * The attribute is always returned, regardless of the contents of the "attributes" parameter.
     * For example, "id" is always returned to identify a SCIM resource.
     */
    const RETURNED_ALWAYS = 'always';

    /**
     * The attribute is never returned.  This may occur because the original attribute value (e.g., a hashed value) is not
     * retained by the service provider.  A service provider MAY allow attributes to be used in a search filter.
     */
    const RETURNED_NEVER = 'never';

    /**
     * The attribute is returned by default in all SCIM operation responses where attribute values are returned.
     * If the GET request "attributes" parameter is specified, attribute values are only returned if the attribute
     * is named in the "attributes" parameter.  DEFAULT.
     */
    const RETURNED_DEFAULT = 'default';

    /**
     * The attribute is returned in response to any PUT, POST, or PATCH operations if the attribute was specified
     * by the client (for example, the attribute was modified). The attribute is returned in a SCIM query operation
     * only if specified in the "attributes" parameter.
     */
    const RETURNED_REQUEST = 'request';

    /**
     * The values are not intended to be unique in any way. DEFAULT.
     */
    const UNIQUENESS_NONE = 'none';

    /**
     * The value SHOULD be unique within the context of the current SCIM endpoint (or tenancy) and MAY be globally unique
     * (e.g., a "username", email address, or other server-generated key or counter).
     * No two resources on the same server SHOULD possess the same value.
     */
    const UNIQUENESS_SERVER = 'server';

    /**
     * The value SHOULD be globally unique (e.g., an email address, a GUID, or other value).
     * No two resources on any server SHOULD possess the same value.
     */
    const UNIQUENESS_GLOBAL = 'global';
}
