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
 * @method static ReferenceTypeValue SCIM()
 * @method static ReferenceTypeValue USER()
 * @method static ReferenceTypeValue GROUP()
 * @method static ReferenceTypeValue EXTERNAL()
 * @method static ReferenceTypeValue URI()
 */
class ReferenceTypeValue extends AbstractEnum
{
    const SCIM = 'scim';
    const USER = 'User';
    const GROUP = 'Group';
    const EXTERNAL = 'external';
    const URI = 'uri';
}
