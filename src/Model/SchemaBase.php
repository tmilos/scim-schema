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

abstract class SchemaBase
{
    /**
     * @return string[]
     */
    public function getSchemas()
    {
        return [$this->getSchemaId()];
    }

    /**
     * @return string
     */
    abstract public function getSchemaId();
}
