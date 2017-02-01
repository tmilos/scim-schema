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

class Meta implements SerializableInterface
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

    /**
     * @param array $data
     *
     * @return Meta
     */
    public static function deserializeObject(array $data)
    {
        $result = new static($data['resourceType'], isset($data['created']) ? Helper::string2dateTime($data['created']) : null);
        if (isset($data['lastModified'])) {
            $result->lastModified = Helper::string2dateTime($data['lastModified']);
        }
        if (isset($data['location'])) {
            $result->location = $data['location'];
        }
        if (isset($data['version'])) {
            $result->version = $data['version'];
        }

        return $result;
    }

    /**
     * @param string    $resourceType
     * @param \DateTime $createdAt
     */
    public function __construct($resourceType, \DateTime $createdAt = null)
    {
        $this->resourceType = $resourceType;
        $this->created = $createdAt;
    }

    /**
     * @return string
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
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
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return array
     */
    public function serializeObject()
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
