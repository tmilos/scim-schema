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

use Tmilos\ScimSchema\Model\Schema\Attribute;
use Tmilos\ScimSchema\Model\Schema\AttributeTypeValue;
use Tmilos\ScimSchema\Model\Schema\MutabilityValue;
use Tmilos\ScimSchema\Model\Schema\ReferenceTypeValue;
use Tmilos\ScimSchema\Model\Schema\ReturnedValue;
use Tmilos\ScimSchema\Model\Schema;
use Tmilos\ScimSchema\Model\Schema\UniquenessValue;

class SchemaBuilder
{
    private static $map = [
        Schema::GROUP => 'getGroup',
        Schema::USER => 'getUser',
        Schema::SCHEMA => 'getSchema',
        Schema::RESOURCE_TYPE => 'getResourceType',
        Schema::SERVICE_PROVIDER_CONFIG => 'getServiceProviderConfig',
    ];

    /** @var string */
    private $locationBase = 'http://localhost';

    /**
     * @param string $locationBase
     */
    public function __construct($locationBase = 'http://localhost')
    {
        $this->setLocationBase($locationBase);
    }

    /**
     * @param string $locationBase
     */
    public function setLocationBase($locationBase)
    {
        $this->locationBase = rtrim($locationBase, '/');
    }

    /**
     * @param string $schema
     *
     * @return Schema
     */
    public function get($schema)
    {
        return $this->{self::$map[$schema]}();
    }

    /**
     * @return Schema
     */
    public function getGroup()
    {
        $schema = new Schema(Schema::GROUP);
        $schema->getMeta()->setLocation($this->locationBase.'/Schemas/'.$schema->getId());
        $schema->setName('Group');
        $schema->setDescription('Group');
        $schema->addAttribute(
            AttributeBuilder::create('displayName', AttributeTypeValue::STRING, 'A human-readable name for the Group. REQUIRED.')->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('members', AttributeTypeValue::COMPLEX, 'A list of members of the Group.')
                ->setRequired(true)
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('value', AttributeTypeValue::STRING, 'Identifier of the member of this Group.')
                        ->setMutability(MutabilityValue::IMMUTABLE)
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('$ref', AttributeTypeValue::REFERENCE, 'The URI corresponding to a SCIM resource that is a member of this Group.')
                        ->setMutability(MutabilityValue::IMMUTABLE)
                        ->setReferenceTypes([ReferenceTypeValue::USER, ReferenceTypeValue::GROUP])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('type', AttributeTypeValue::STRING, "A label indicating the type of resource, e.g., 'User' or 'Group'.")
                        ->setMutability(MutabilityValue::IMMUTABLE)
                        ->setCanonicalValues(['User', 'Group'])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('display', AttributeTypeValue::STRING, 'Display name for the member')
                        ->setMutability(MutabilityValue::IMMUTABLE)
                        ->getAttribute()
                )
                ->getAttribute()
        );

        return $schema;
    }

    /**
     * @return Schema
     */
    public function getUser()
    {
        $schema = new Schema(Schema::USER);
        $schema->getMeta()->setLocation($this->locationBase.'/Schemas/'.$schema->getId());
        $schema->setName('User');
        $schema->setDescription('User Account');
        $schema->addAttribute(
            AttributeBuilder::create('userName', AttributeTypeValue::STRING)
                ->setDescription('Unique identifier for the User, typically used by the user to directly authenticate to the service provider. Each User MUST include a non-empty userName value. This identifier MUST be unique across the service provider\'s entire set of Users. REQUIRED.')
                ->setUniqueness(UniquenessValue::SERVER)
                ->setRequired(true)
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('name', AttributeTypeValue::COMPLEX)
                ->setDescription('The components of the user\'s real name. Providers MAY return just the full name as a single string in the formatted sub-attribute, or they MAY return just the individual component attributes using the other sub-attributes, or they MAY return both.  If both variants are returned, they SHOULD be describing the same name, with the formatted name indicating how the component attributes should be combined.')
                ->addSubAttribute(
                    AttributeBuilder::create('formatted', AttributeTypeValue::STRING)
                        ->setDescription("The full name, including all middle names, titles, and suffixes as appropriate, formatted for display (e.g., 'Ms. Barbara J Jensen, III').")
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('familyName', AttributeTypeValue::STRING)
                        ->setDescription("The family name of the User, or last name in most Western languages (e.g., 'Jensen' given the full name 'Ms. Barbara J Jensen, III').")
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('givenName', AttributeTypeValue::STRING)
                        ->setDescription("The given name of the User, or first name in most Western languages (e.g., 'Barbara' given the full name 'Ms. Barbara J Jensen, III').")
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('middleName', AttributeTypeValue::STRING)
                        ->setDescription("The middle name(s) of the User (e.g., 'Jane' given the full name 'Ms. Barbara J Jensen, III').")
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('honorificPrefix', AttributeTypeValue::STRING)
                        ->setDescription("The honorific prefix(es) of the User, or title in most Western languages (e.g., 'Ms.' given the full name 'Ms. Barbara J Jensen, III').")
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('honorificSuffix', AttributeTypeValue::STRING)
                        ->setDescription("The honorific suffix(es) of the User, or suffix in most Western languages (e.g., 'III' given the full name 'Ms. Barbara J Jensen, III').")
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('displayName', AttributeTypeValue::STRING)
                ->setDescription('The name of the User, suitable for display to end-users.  The name SHOULD be the full name of the User being described, if known.')
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('nickName', AttributeTypeValue::STRING)
                ->setDescription("The casual way to address the user in real life, e.g., 'Bob' or 'Bobby' instead of 'Robert'.  This attribute SHOULD NOT be used to represent a User's username (e.g., 'bjensen' or 'mpepperidge').")
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('profileUrl', AttributeTypeValue::REFERENCE)
                ->setDescription("A fully qualified URL pointing to a page representing the User's online profile.")
                ->setReferenceTypes([ReferenceTypeValue::EXTERNAL])
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('title', AttributeTypeValue::STRING)
                ->setDescription("The user's title, such as \"Vice President\".")
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('userType', AttributeTypeValue::STRING)
                ->setDescription("Used to identify the relationship between the organization and the user. Typical values used might be 'Contractor', 'Employee', 'Intern', 'Temp', 'External', and 'Unknown', but any value may be used.")
                ->setCanonicalValues(['Contractor', 'Employee', 'Intern', 'Temp', 'External', 'Internal', 'Unknown'])
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('preferredLanguage', AttributeTypeValue::STRING)
                ->setDescription("Indicates the User's preferred written or spoken language.  Generally used for selecting a localized user interface; e.g., 'en_US' specifies the language English and country US.")
                ->setCanonicalValues(['zh_CN', 'en_US', 'en_CA'])
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('locale', AttributeTypeValue::STRING)
                ->setDescription("Used to indicate the User's default location for purposes of localizing items such as currency, date time format, or numerical representations.")
                ->setCanonicalValues(['en-CA', 'fr-CA', 'en-US', 'zh-CN'])
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('timezone', AttributeTypeValue::STRING)
                ->setDescription("The User's time zone in the 'Olson' time zone database format, e.g., 'America/Los_Angeles'.")
                ->setCanonicalValues(['Asia/Shanghai', 'America/New_York', 'America/Toronto'])
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('active', AttributeTypeValue::BOOLEAN)
                ->setDescription("A Boolean value indicating the User's administrative status.")
                ->setRequired(true)
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('password', AttributeTypeValue::STRING)
                ->setDescription("The User's cleartext password.  This attribute is intended to be used as a means to specify an initial password when creating a new User or to reset an existing User's password.")
                ->setMutability(MutabilityValue::WRITE_ONLY)
                ->setReturned(ReturnedValue::NEVER)
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('emails', AttributeTypeValue::COMPLEX)
                ->setDescription("Email addresses for the user.  The value SHOULD be canonicalized by the service provider, e.g., 'bjensen@example.com' instead of 'bjensen@EXAMPLE.COM'. Canonical type values of 'work', 'home', and 'other'.")
                ->setRequired(true)
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('value', AttributeTypeValue::STRING)
                        ->setDescription("Email addresses for the user.  The value SHOULD be canonicalized by the service provider, e.g., 'bjensen@example.com' instead of 'bjensen@EXAMPLE.COM'.")
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('display', AttributeTypeValue::STRING)
                        ->setDescription('Display value for an email')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('type', AttributeTypeValue::STRING)
                        ->setDescription("A label indicating the attribute's function, e.g., 'work' or 'home'.")
                        ->setCanonicalValues(['work', 'home', 'other'])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('primary', AttributeTypeValue::BOOLEAN)
                        ->setDescription("A Boolean value indicating the 'primary' or preferred attribute value for this attribute, e.g., the preferred mailing address or primary email address.  The primary attribute value 'true' MUST appear no more than once.")
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('phoneNumbers', AttributeTypeValue::COMPLEX)
                ->setDescription("Phone numbers for the User.  The value SHOULD be canonicalized by the service provider according to the format specified in RFC 3966, e.g., 'tel:+1-201-555-0123'. Canonical type values of 'work', 'home', 'mobile', 'fax', 'pager', and 'other'.")
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('value', AttributeTypeValue::STRING)
                        ->setDescription('Phone number of the User.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('display', AttributeTypeValue::STRING)
                        ->setDescription('Display value for a phone number')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('type', AttributeTypeValue::STRING)
                        ->setDescription("A label indicating the attribute's function, e.g., 'work', 'home', 'mobile'.")
                        ->setCanonicalValues(['work', 'home', 'mobile', 'fax', 'pager', 'other'])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('primary', AttributeTypeValue::BOOLEAN)
                        ->setDescription("A Boolean value indicating the 'primary' or preferred attribute value for this attribute, e.g., the preferred phone number or primary phone number.  The primary attribute value 'true' MUST appear no more than once.")
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('ims', AttributeTypeValue::COMPLEX)
                ->setDescription('Instant messaging addresses for the User.')
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('value', AttributeTypeValue::STRING)
                        ->setDescription('Instant messaging address for the User.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('display', AttributeTypeValue::STRING)
                        ->setDescription('Display value for a IMS')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('type', AttributeTypeValue::STRING)
                        ->setDescription("A label indicating the attribute's function, e.g., 'aim', 'gtalk', 'xmpp'.")
                        ->setCanonicalValues(['aim', 'gtalk', 'icq', 'xmpp', 'msn', 'skype', 'qq', 'yahoo', 'wechat', 'facebook', 'weibo', 'other'])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('primary', AttributeTypeValue::BOOLEAN)
                        ->setDescription("A Boolean value indicating the 'primary' or preferred attribute value for this attribute, e.g., the preferred messenger or primary messenger.  The primary attribute value 'true' MUST appear no more than once.")
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('photos', AttributeTypeValue::COMPLEX)
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('value', AttributeTypeValue::REFERENCE)
                        ->setDescription('URL of a photo of the User.')
                        ->setReferenceTypes([ReferenceTypeValue::EXTERNAL])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('type', AttributeTypeValue::STRING)
                        ->setDescription("A label indicating the attribute's function, i.e., 'photo' or 'thumbnail'.")
                        ->setCanonicalValues(['photo', 'thumbnail'])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('primary', AttributeTypeValue::BOOLEAN)
                        ->setDescription("A Boolean value indicating the 'primary' or preferred attribute value for this attribute, e.g., the preferred photo or thumbnail.  The primary attribute value 'true' MUST appear no more than once.")
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('addresses', AttributeTypeValue::COMPLEX)
                ->setDescription("A physical mailing address for this User. Canonical type values of 'work', 'home', and 'other'.  This attribute is a complex type with the following sub-attributes.")
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('formatted', AttributeTypeValue::STRING)
                        ->setDescription('The full mailing address, formatted for display or use with a mailing label.  This attribute MAY contain newlines.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('streetAddress', AttributeTypeValue::STRING)
                        ->setDescription('The full street address component, which may include house number, street name, P.O. box, and multi-line extended street address information.  This attribute MAY contain newlines.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('locality', AttributeTypeValue::STRING)
                        ->setDescription('The city or locality component.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('region', AttributeTypeValue::STRING)
                        ->setDescription('The state or region component.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('postalCode', AttributeTypeValue::STRING)
                        ->setDescription('The zip code or postal code component.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('country', AttributeTypeValue::STRING)
                        ->setDescription('The country name component.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('type', AttributeTypeValue::STRING)
                        ->setDescription("A label indicating the attribute's function, e.g., 'work' or 'home'.")
                        ->setCanonicalValues(['work', 'home', 'id', 'driverlicense', 'other'])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('primary', AttributeTypeValue::BOOLEAN)
                        ->setDescription("A Boolean value indicating the 'primary' or preferred attribute value for this attribute. The primary attribute value 'true' MUST appear no more than once.")
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('groups', AttributeTypeValue::COMPLEX)
                ->setDescription('A list of groups to which the user belongs, either through direct membership, through nested groups, or dynamically calculated.')
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('value', AttributeTypeValue::STRING)
                        ->setDescription("The identifier of the User's group.")
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('$ref', AttributeTypeValue::REFERENCE)
                        ->setDescription("The URI of the corresponding 'Group' resource to which the user belongs.")
                        ->setReferenceTypes([ReferenceTypeValue::GROUP])
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('display', AttributeTypeValue::STRING)
                        ->setDescription('A human-readable name, primarily used for display purposes.  READ-ONLY.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('type', AttributeTypeValue::STRING)
                        ->setDescription("A label indicating the attribute's function, e.g., 'direct' or 'indirect'.")
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setCanonicalValues(['direct', 'indirect'])
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('entitlements', AttributeTypeValue::COMPLEX)
                ->setDescription('A list of entitlements for the User that represent a thing the User has.')
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('value', AttributeTypeValue::STRING)
                        ->setDescription('The value of an entitlement.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('display', AttributeTypeValue::STRING)
                        ->setDescription('The display value of an entitlement.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('type', AttributeTypeValue::STRING)
                        ->setDescription("A label indicating the attribute's function.")
                        ->setCanonicalValues(['permission'])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('primary', AttributeTypeValue::BOOLEAN)
                        ->setDescription("A Boolean value indicating the 'primary' or preferred attribute value for this attribute. The primary attribute value 'true' MUST appear no more than once.")
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('roles', AttributeTypeValue::COMPLEX)
                ->setDescription("A list of roles for the User that collectively represent who the User is, e.g., 'Student', 'Faculty'.")
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('value', AttributeTypeValue::STRING)
                        ->setDescription('The value of a role.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('display', AttributeTypeValue::STRING)
                        ->setDescription('The display value of a role.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('type', AttributeTypeValue::STRING)
                        ->setDescription("A label indicating the attribute's function.")
                        ->setCanonicalValues(['permission'])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('primary', AttributeTypeValue::BOOLEAN)
                        ->setDescription("A Boolean value indicating the 'primary' or preferred attribute value for this attribute. The primary attribute value 'true' MUST appear no more than once.")
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('x509Certificates', AttributeTypeValue::COMPLEX)
                ->setDescription('A list of certificates issued to the User.')
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('value', AttributeTypeValue::STRING)
                        ->setDescription('The value of a certificate.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('display', AttributeTypeValue::STRING)
                        ->setDescription('The display value of a certificate.')
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('type', AttributeTypeValue::STRING)
                        ->setDescription("A label indicating the attribute's function.")
                        ->setCanonicalValues(['permission'])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('primary', AttributeTypeValue::BOOLEAN)
                        ->setDescription("A Boolean value indicating the 'primary' or preferred attribute value for this attribute. The primary attribute value 'true' MUST appear no more than once.")
                        ->getAttribute()
                )
                ->getAttribute()
        );

        return $schema;
    }

    /**
     * @return Schema
     */
    public function getSchema()
    {
        $schema = new Schema(Schema::SCHEMA);
        $schema->getMeta()->setLocation($this->locationBase.'/Schemas/'.$schema->getId());
        $schema->setName('Schema');
        $schema->setDescription('Specifies the schema that describes a SCIM schema');
        $schema->addAttribute(
            AttributeBuilder::create('id', AttributeTypeValue::STRING)
                ->setDescription('The unique URI of the schema. When applicable, service providers MUST specify the URI.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('name', AttributeTypeValue::STRING)
                ->setDescription("The schema's human-readable name.  When applicable, service providers MUST specify the name, e.g., 'User'.")
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('description', AttributeTypeValue::STRING)
                ->setDescription("The schema's human-readable name.  When applicable, service providers MUST specify the name, e.g., 'User'.")
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->getAttribute()
        );

        $schema->addAttribute(
            AttributeBuilder::create('attributes', AttributeTypeValue::COMPLEX)
                ->setDescription('A complex attribute that includes the attributes of a schema.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->setMultiValued(true)
                ->addSubAttributes(
                    $this->getSubAttributes()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('subAttributes', AttributeTypeValue::COMPLEX)
                        ->setDescription('Used to define the sub-attributes of a complex attribute.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setMultiValued(true)
                        ->addSubAttributes($this->getSubAttributes())
                        ->getAttribute()
                )
                ->getAttribute()
        );

        return $schema;
    }

    /**
     * @return Schema
     */
    public function getResourceType()
    {
        $schema = new Schema(Schema::RESOURCE_TYPE);
        $schema->getMeta()->setLocation($this->locationBase.'/Schemas/'.$schema->getId());
        $schema->setName('ResourceType');
        $schema->setDescription('Specifies the schema that describes a SCIM resource type');
        $schema->addAttribute(
            AttributeBuilder::create('id', AttributeTypeValue::STRING)
                ->setDescription("The resource type's server unique id. May be the same as the 'name' attribute.")
            ->setMutability(MutabilityValue::READ_ONLY)
            ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('name', AttributeTypeValue::STRING)
                ->setDescription("The resource type name.  When applicable, service providers MUST specify the name, e.g., 'User'.")
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('description', AttributeTypeValue::STRING)
                ->setDescription("The resource type's human-readable description. When applicable, service providers MUST specify the description.")
                ->setMutability(MutabilityValue::READ_ONLY)
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('endpoint', AttributeTypeValue::REFERENCE)
                ->setDescription("The resource type's HTTP-addressable endpoint relative to the Base URL, e.g., '/Users'.")
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->setReferenceTypes([ReferenceTypeValue::URI])
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('schema', AttributeTypeValue::REFERENCE)
                ->setDescription("The resource type's primary/base schema URI.")
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->setCaseExact(true)
                ->setReferenceTypes([ReferenceTypeValue::URI])
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('schemaExtensions', AttributeTypeValue::COMPLEX)
                ->setDescription("A list of URIs of the resource type's schema extensions.")
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('schema', AttributeTypeValue::REFERENCE)
                        ->setDescription('The URI of a schema extension.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->setCaseExact(true)
                        ->setReferenceTypes([ReferenceTypeValue::URI])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('required', AttributeTypeValue::BOOLEAN)
                        ->setDescription('A Boolean value that specifies whether or not the schema extension is required for the resource type.  If true, a resource of this type MUST include this schema extension and also include any attributes declared as required in this schema extension. If false, a resource of this type MAY omit this schema extension.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->getAttribute()
        );

        return $schema;
    }

    /**
     * @return Schema
     */
    public function getServiceProviderConfig()
    {
        $schema = new Schema(Schema::SERVICE_PROVIDER_CONFIG);
        $schema->getMeta()->setLocation($this->locationBase.'/Schemas/'.$schema->getId());
        $schema->setName('Service Provider Configuration');
        $schema->setDescription("Schema for representing the service provider's configuration");
        $schema->addAttribute(
            AttributeBuilder::create('documentationUri', AttributeTypeValue::REFERENCE)
                ->setDescription("An HTTP-addressable URL pointing to the service provider's human-consumable help documentation.")
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setReferenceTypes([ReferenceTypeValue::EXTERNAL])
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('patch', AttributeTypeValue::COMPLEX)
                ->setDescription('A complex type that specifies PATCH configuration options.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->addSubAttribute(
                    AttributeBuilder::create('supported', AttributeTypeValue::BOOLEAN)
                        ->setDescription('A Boolean value specifying whether or not the operation is supported.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('bulk', AttributeTypeValue::COMPLEX)
                ->setDescription('A complex type that specifies bulk configuration options.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->addSubAttribute(
                    AttributeBuilder::create('supported', AttributeTypeValue::BOOLEAN)
                        ->setDescription('A Boolean value specifying whether or not the operation is supported.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('maxOperations', AttributeTypeValue::INTEGER)
                        ->setDescription('An integer value specifying the maximum number of operations.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('maxPayloadSize', AttributeTypeValue::INTEGER)
                        ->setDescription('An integer value specifying the maximum payload size in bytes.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('filter', AttributeTypeValue::COMPLEX)
                ->setDescription('A complex type that specifies FILTER options.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->addSubAttribute(
                    AttributeBuilder::create('supported', AttributeTypeValue::BOOLEAN)
                        ->setDescription('A Boolean value specifying whether or not the operation is supported.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('maxResults', AttributeTypeValue::INTEGER)
                        ->setDescription('An integer value specifying the maximum number of resources returned in a response.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('changePassword', AttributeTypeValue::COMPLEX)
                ->setDescription('A complex type that specifies configuration options related to changing a password.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->addSubAttribute(
                    AttributeBuilder::create('supported', AttributeTypeValue::BOOLEAN)
                        ->setDescription('A Boolean value specifying whether or not the operation is supported.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('sort', AttributeTypeValue::COMPLEX)
                ->setDescription('A complex type that specifies sort result options.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->addSubAttribute(
                    AttributeBuilder::create('supported', AttributeTypeValue::BOOLEAN)
                        ->setDescription('A Boolean value specifying whether or not the operation is supported.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('etag', AttributeTypeValue::COMPLEX)
                ->setDescription('A complex type that specifies etag options')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->addSubAttribute(
                    AttributeBuilder::create('supported', AttributeTypeValue::BOOLEAN)
                        ->setDescription('A Boolean value specifying whether or not the operation is supported.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('authenticationSchemes', AttributeTypeValue::COMPLEX)
                ->setDescription('A complex type that specifies supported authentication scheme properties.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->setMultiValued(true)
                ->addSubAttribute(
                    AttributeBuilder::create('type', AttributeTypeValue::STRING)
                        ->setDescription('Specifies the type of the authentication scheme')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->setCanonicalValues(['oauth2', 'httpbasic'])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('name', AttributeTypeValue::STRING)
                        ->setDescription('The common authentication scheme name, e.g., HTTP Basic.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('description', AttributeTypeValue::STRING)
                        ->setDescription('A description of the authentication scheme.')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('specUri', AttributeTypeValue::REFERENCE)
                        ->setDescription("An HTTP-addressable URL pointing to the authentication scheme's specification.")
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(false)
                        ->setReferenceTypes([ReferenceTypeValue::EXTERNAL])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('documentationUri', AttributeTypeValue::REFERENCE)
                        ->setDescription("An HTTP-addressable URL pointing to the authentication scheme's usage documentation.")
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(false)
                        ->setReferenceTypes([ReferenceTypeValue::EXTERNAL])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('primary', AttributeTypeValue::BOOLEAN)
                        ->setDescription('A boolean indicates whether this authentication schema is primary')
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->setRequired(false)
                        ->getAttribute()
                )
                ->getAttribute()
        );

        return $schema;
    }

    /**
     * @return Schema
     */
    public function getEnterpriseUser()
    {
        $schema = new Schema(Schema::ENTERPRISE_USER);
        $schema->getMeta()->setLocation($this->locationBase.'/Schemas/'.$schema->getId());
        $schema->setName('EnterpriseUser');
        $schema->setDescription('Enterprise User');
        $schema->addAttribute(
            AttributeBuilder::create('employeeNumber', AttributeTypeValue::STRING)
                ->setDescription('Numeric or alphanumeric identifier assigned to a person, typically based on order of hire or association with an organization.')
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('costCenter', AttributeTypeValue::STRING)
                ->setDescription('Identifies the name of a cost center.')
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('organization', AttributeTypeValue::STRING)
                ->setDescription('Identifies the name of an organization.')
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('division', AttributeTypeValue::STRING)
                ->setDescription('Identifies the name of a division.')
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('department', AttributeTypeValue::STRING)
                ->setDescription('Identifies the name of a department.')
                ->getAttribute()
        );
        $schema->addAttribute(
            AttributeBuilder::create('manager', AttributeTypeValue::COMPLEX)
                ->setDescription("The User's manager.  A complex type that optionally allows service providers to represent organizational hierarchy by referencing the 'id' attribute of another User.")
                ->addSubAttribute(
                    AttributeBuilder::create('value', AttributeTypeValue::STRING)
                        ->setDescription("The id of the SCIM resource representing the User's manager.  REQUIRED.")
                        ->setRequired(true)
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('$ref', AttributeTypeValue::REFERENCE)
                        ->setDescription("The URI of the SCIM resource representing the User's manager.  REQUIRED.")
                        ->setRequired(true)
                        ->setReferenceTypes([ReferenceTypeValue::USER])
                        ->getAttribute()
                )
                ->addSubAttribute(
                    AttributeBuilder::create('displayName', AttributeTypeValue::STRING)
                        ->setDescription("The displayName of the User's manager. OPTIONAL and READ-ONLY.")
                        ->setMutability(MutabilityValue::READ_ONLY)
                        ->getAttribute()
                )
                ->getAttribute()
        );

        return $schema;
    }

    /**
     * @return Attribute[]
     */
    private function getSubAttributes()
    {
        return [
            AttributeBuilder::create('name', AttributeTypeValue::STRING)
                ->setDescription("The attribute's name.")
                ->setRequired(true)
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setCaseExact(true)
                ->getAttribute(),
            AttributeBuilder::create('type', AttributeTypeValue::STRING)
                ->setDescription("The attribute's data type. Valid values include 'string', 'complex', 'boolean', 'decimal', 'integer', 'dateTime', 'reference'.")
                ->setRequired(true)
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setCaseExact(true)
                ->setCanonicalValues(['string', 'complex', 'boolean', 'decimal', 'integer', 'dateTime', 'reference'])
                ->getAttribute(),
            AttributeBuilder::create('multiValued', AttributeTypeValue::BOOLEAN)
                ->setDescription("A Boolean value indicating an attribute's plurality.")
                ->setRequired(true)
                ->setMutability(MutabilityValue::READ_ONLY)
                ->getAttribute(),
            AttributeBuilder::create('description', AttributeTypeValue::STRING)
                ->setDescription('A human-readable description of the attribute.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->getAttribute(),
            AttributeBuilder::create('required', AttributeTypeValue::BOOLEAN)
                ->setDescription('A boolean value indicating whether or not the attribute is required.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setRequired(true)
                ->getAttribute(),
            AttributeBuilder::create('canonicalValues', AttributeTypeValue::STRING)
                ->setDescription("A collection of canonical values. When applicable, service providers MUST specify the canonical types, e.g., 'work', 'home'.")
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setMultiValued(true)
                ->setCaseExact(true)
                ->getAttribute(),
            AttributeBuilder::create('caseExact', AttributeTypeValue::BOOLEAN)
                ->setDescription('A Boolean value indicating whether or not a string attribute is case sensitive.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->getAttribute(),
            AttributeBuilder::create('mutability', AttributeTypeValue::STRING)
                ->setDescription('Indicates whether or not an attribute is modifiable.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setCaseExact(true)
                ->setCanonicalValues([MutabilityValue::READ_ONLY, MutabilityValue::READ_WRITE, MutabilityValue::IMMUTABLE, MutabilityValue::WRITE_ONLY])
                ->getAttribute(),
            AttributeBuilder::create('returned', AttributeTypeValue::STRING)
                ->setDescription('Indicates when an attribute is returned in a response (e.g., to a query).')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setCaseExact(true)
                ->setCanonicalValues([ReturnedValue::ALWAYS, ReturnedValue::NEVER, ReturnedValue::BY_DEFAULT, ReturnedValue::REQUEST])
                ->getAttribute(),
            AttributeBuilder::create('uniqueness', AttributeTypeValue::STRING)
                ->setDescription('Indicates how unique a value must be.')
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setCaseExact(true)
                ->setCanonicalValues(['none', 'server', 'global'])
                ->getAttribute(),
            AttributeBuilder::create('referenceTypes', AttributeTypeValue::STRING)
                ->setDescription("Used only with an attribute of type 'reference'.  Specifies a SCIM resourceType that a reference attribute MAY refer to, e.g., 'User'.")
                ->setMutability(MutabilityValue::READ_ONLY)
                ->setCaseExact(true)
                ->setCanonicalValues([ReferenceTypeValue::SCIM, ReferenceTypeValue::EXTERNAL, ReferenceTypeValue::URI, ReferenceTypeValue::USER, ReferenceTypeValue::GROUP])
                ->getAttribute(),
        ];
    }
}
