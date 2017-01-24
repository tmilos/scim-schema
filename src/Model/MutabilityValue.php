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
 * @method static MutabilityValue READ_ONLY()
 * @method static MutabilityValue READ_WRITE()
 * @method static MutabilityValue IMMUTABLE()
 * @method static MutabilityValue WRITE_ONLY()
 */
class MutabilityValue extends AbstractEnum
{
    /**
     * The attribute SHALL NOT be modified.
     */
    const READ_ONLY = 'readOnly';

    /**
     * The attribute MAY be updated and read at any time. This is the default value.
     */
    const READ_WRITE = 'readWrite';

    /**
     * The attribute MAY be defined at resource creation (e.g., POST) or at record replacement via a request (e.g., a PUT).
     * The attribute SHALL NOT be updated.
     */
    const IMMUTABLE = 'immutable';

    /**
     * The attribute MAY be updated at any time.  Attribute values SHALL NOT be returned (e.g., because the value is a stored hash).
     * Note: An attribute with a mutability of "writeOnly" usually also has a returned setting of "never".
     */
    const WRITE_ONLY = 'writeOnly';
}
