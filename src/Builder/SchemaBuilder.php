<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Builder;

use Tmilos\ScimSchema\Model\Schema;

abstract class SchemaBuilder
{
    protected static $schemas = [];

    /** @var string */
    private $schemasEndpointUrl;

    /**
     * @param string $schemasEndpointUrl
     */
    public function __construct($schemasEndpointUrl)
    {
        $this->setSchemasEndpointUrl($schemasEndpointUrl);
    }

    /**
     * @param string $schemasEndpointUrl
     */
    public function setSchemasEndpointUrl($schemasEndpointUrl)
    {
        $this->schemasEndpointUrl = rtrim($schemasEndpointUrl, '/').'/';
    }

    /**
     * @param string $schemaId
     *
     * @return \Tmilos\ScimSchema\Model\v1\Schema|\Tmilos\ScimSchema\Model\v2\Schema|Schema
     */
    public function get($schemaId)
    {
        $class = $this->getSchemaClass();
        $data = static::$schemas[$schemaId];
        /** @var Schema $schema */
        $schema = call_user_func([$class, 'deserializeObject'], $data);
        $schema->getMeta()->setLocation($this->schemasEndpointUrl.$schemaId);

        return $schema;
    }

    /**
     * @return string
     */
    abstract protected function getSchemaClass();
}
