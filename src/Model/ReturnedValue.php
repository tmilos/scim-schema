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

use Tmilos\Value\AbstractEnum;

/**
 * @method static ReturnedValue ALWAYS()
 * @method static ReturnedValue NEVER()
 * @method static ReturnedValue BY_DEFAULT()
 * @method static ReturnedValue REQUEST()
 */
class ReturnedValue extends AbstractEnum
{
    /**
     * The attribute is always returned, regardless of the contents of the "attributes" parameter.
     * For example, "id" is always returned to identify a SCIM resource.
     */
    const ALWAYS = 'always';

    /**
     * The attribute is never returned.  This may occur because the original attribute value (e.g., a hashed value) is not
     * retained by the service provider.  A service provider MAY allow attributes to be used in a search filter.
     */
    const NEVER = 'never';

    /**
     * The attribute is returned by default in all SCIM operation responses where attribute values are returned.
     * If the GET request "attributes" parameter is specified, attribute values are only returned if the attribute
     * is named in the "attributes" parameter.  DEFAULT.
     */
    const BY_DEFAULT = 'default';

    /**
     * The attribute is returned in response to any PUT, POST, or PATCH operations if the attribute was specified
     * by the client (for example, the attribute was modified). The attribute is returned in a SCIM query operation
     * only if specified in the "attributes" parameter.
     */
    const REQUEST = 'request';
}
