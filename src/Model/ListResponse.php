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

abstract class ListResponse extends SchemaBase implements SerializableInterface
{
    /** @var int */
    protected $totalResults;

    /** @var int */
    protected $startIndex;

    /** @var int */
    protected $itemsPerPage;

    /** @var array|SerializableInterface[] */
    protected $resources;

    /**
     * @param array|SerializableInterface[] $resources
     * @param int                           $totalResults
     * @param int                           $startIndex
     * @param int                           $itemsPerPage
     */
    public function __construct(array $resources, $totalResults, $startIndex, $itemsPerPage)
    {
        $this->resources = $resources;
        $this->totalResults = $totalResults;
        $this->startIndex = $startIndex;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @return int
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }

    /**
     * @return int
     */
    public function getStartIndex()
    {
        return $this->startIndex;
    }

    /**
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * @return array|SerializableInterface[]
     */
    public function getResources()
    {
        return $this->resources;
    }

    public function serializeObject()
    {
        $result = [
            'schemas' => $this->getSchemas(),
            'totalResults' => $this->totalResults,
            'Resources' => [],
        ];
        if ($this->startIndex) {
            $result['startIndex'] = $this->startIndex;
            $result['itemsPerPage'] = $this->itemsPerPage;
        }

        foreach ($this->resources as $resource) {
            if ($resource instanceof SerializableInterface) {
                $result['Resources'][] = $resource->serializeObject();
            } elseif (!is_array($resource)) {
                throw new \InvalidArgumentException('Resource must implement SerializableInterface or already be serialized to array');
            }
            $result['Resources'][] = $resource;
        }

        return $result;
    }
}
