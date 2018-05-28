<?php
/**
 * Created by IntelliJ IDEA.
 * User: fsilva
 * Date: 5/28/18
 * Time: 13:49
 */

namespace We\DataHub;

use JsonSerializable;

/**
 * Class Document is a container for the data `atoms` that will be sent to
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
     * @var string[]
     */
    private $fields;

    public function __construct(string $id, string $namespace, string $documentType, array $fields)
    {
        $this->documentId = $id;
        $this->namespace = $namespace;
        $this->documentType = $documentType;
        $this->fields = $fields;
    }

    /**
     * @return string
     */
    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    /**
     * @return string[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}