<?php

/*
 * This file is part of the tmilos/scim-schema package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\ScimSchema\Model\SPC;

class AuthenticationScheme
{
    const OAUTH = 'oauth';
    const OAUTH2 = 'oauth2';
    const OAUTH_BEARER_TOKEN = 'oauthbearertoken';
    const HTTP_BASIC = 'httpbasic';
    const HTTP_DIGEST = 'httpdigest';

    /** @var string */
    private $type;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var string */
    private $specUri;

    /** @var string */
    private $documentationUri;

    /**
     * @param array $arr
     *
     * @return AuthenticationScheme
     */
    public static function fromArray(array $arr)
    {
        return new static(
            $arr['type'],
            $arr['name'],
            $arr['description'],
            isset($arr['specUri']) ? $arr['specUri'] : null,
            isset($arr['documentationUri']) ? $arr['documentationUri'] : null
        );
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $description
     * @param string $specUri
     * @param string $documentationUri
     */
    public function __construct($type, $name, $description, $specUri, $documentationUri)
    {
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;
        $this->specUri = $specUri;
        $this->documentationUri = $documentationUri;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getSpecUri()
    {
        return $this->specUri;
    }

    /**
     * @return string
     */
    public function getDocumentationUri()
    {
        return $this->documentationUri;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
        ];

        if ($this->specUri) {
            $result['specUri'] = $this->specUri;
        }
        if ($this->documentationUri) {
            $result['documentationUri'] = $this->documentationUri;
        }

        return $result;
    }
}
