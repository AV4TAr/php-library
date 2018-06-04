<?php
/**
 * Created by IntelliJ IDEA.
 * User: fsilva
 * Date: 5/28/18
 * Time: 13:49
 */

namespace We\DataHub;

use Exception;
use JsonSerializable;
use We\DataHub\Exceptions\InvalidDocumentType;
use We\DataHub\Exceptions\InvalidNamespace;

/**
 * Container for the data that will be sent for indexing.
 *
 * @package We\DataHub
 *
 */
class Document implements JsonSerializable
{

    /**
     * @var string
     */
    private $documentId;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $documentType;

    /**
     * @var Fields
     */
    private $fields;

    /**
     * Document constructor
     *
     * @param string $id will identify the document in the storage engine
     * @param string $namespace namespace the document will live in
     * @param string $documentType structural type of the document as defined in the schema
     * @param Fields $fields the key, value pairs representing field names and values
     * @throws Exception
     *
     */
    public function __construct(string $id, string $namespace, string $documentType, Fields $fields)
    {

        if (!ctype_lower($namespace)) {
            throw new InvalidNamespace("Namespace: Uppercase characters are not allowed.");
        }

        if (!ctype_lower($documentType)) {
            throw new InvalidDocumentType("Document Type: Uppercase characters are not allowed.");
        }

        $this->documentId   = $id;
        $this->namespace    = $namespace;
        $this->documentType = $documentType;
        $this->fields       = $fields;

    }

    /**
     * Uiquely identifies the document in the storage engine
     *
     * @return string
     */
    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    /**
     * Namespace the document will live in
     *
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Structural type of the document as defined in the schema
     *
     * @return string
     */
    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    /**
     * The (key, value) pairs representing field names and values
     *
     * @return Fields
     */
    public function getFields(): Fields
    {
        return $this->fields;
    }

    /**
     * Json Serialization support
     *
     * @return array|mixed list of this object's fields and values.
     *
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}