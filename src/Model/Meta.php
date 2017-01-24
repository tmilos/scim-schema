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

use Tmilos\ScimSchema\Helper;

class Meta
{
    /** @var string */
    protected $resourceType;

    /** @var \DateTime */
    protected $created;

    /** @var \DateTime */
    protected $lastModified;

    /** @var string */
    protected $location;

    /** @var string */
    protected $version;

    /** @var string */
    private $resourceTypeValue;

    /**
     * @param string    $resourceType
     * @param string    $location
     * @param string    $version
     * @param \DateTime $created
     * @param \DateTime $lastModified
     */
    public function __construct($resourceType, $location, $version, \DateTime &$created = null, \DateTime &$lastModified = null)
    {
        $this->resourceType = $resourceType;
        $this->created = $created;
        $this->lastModified = &$lastModified;
        $this->location = $location;
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getResourceType()
    {
        return $this->resourceTypeValue;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @param \DateTime $lastModified
     */
    public function setLastModified(\DateTime $lastModified)
    {
        $this->lastModified = $lastModified;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
            'resourceType' => $this->resourceType,
        ];
        if ($this->created) {
            $result['created'] = Helper::dateTime2string($this->created);
        }
        if ($this->lastModified) {
            $result['lastModified'] = Helper::dateTime2string($this->lastModified);
        }
        if ($this->version) {
            $result['version'] = $this->version;
        }
        if ($this->location) {
            $result['location'] = $this->location;
        }

        return $result;
    }
}
