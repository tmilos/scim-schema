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
 * @method static UniquenessValue NONE()
 * @method static UniquenessValue SERVER()
 * @method static UniquenessValue IN_GLOBAL()
 */
class UniquenessValue extends AbstractEnum
{
    /**
     * The values are not intended to be unique in any way. DEFAULT.
     */
    const NONE = 'none';

    /**
     * The value SHOULD be unique within the context of the current SCIM endpoint (or tenancy) and MAY be globally unique
     * (e.g., a "username", email address, or other server-generated key or counter).
     * No two resources on the same server SHOULD possess the same value.
     */
    const SERVER = 'server';

    /**
     * The value SHOULD be globally unique (e.g., an email address, a GUID, or other value).
     * No two resources on any server SHOULD possess the same value.
     */
    const IN_GLOBAL = 'global';
}
