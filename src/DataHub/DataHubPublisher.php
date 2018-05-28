<?php

namespace We\DataHub;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class DataHubPublisher {

    /** @var AMQPStreamConnection  */
    private $connection;

    /** @var \PhpAmqpLib\Channel\AMQPChannel  */
    private $channel;

    /**
     * @var DataHubConfig
     */
    private $config;

    const DELIVERY_MODE = 2;

    public function __construct(DataHubConfig $config)
    {

        $this->config = $config;

        //Establish connection to AMQP
        if($config->getVhost() == null ) {
            $this->connection = new AMQPStreamConnection(
                $config->getHost(),
                $config->getPort(),
                $config->getUser(),
                $config->getPassword()
            );
        } else {
            $this->connection = new AMQPStreamConnection(
                $config->getHost(),
                $config->getPort(),
                $config->getUser(),
                $config->getPassword(),
                $config->getVhost()
            );
        }

        //Create and declare channel
        $this->channel = $this->connection->channel();

    }

    /**
     *  Encodes fields, creates documents and sends it to the `DataHub` via the configured queue.
     *
     * @param string $id
     * @param string $namespace
     * @param string $documentType
     * @param array $fields
     *
     */
    public function sendDocument(string $id, string $namespace, string $documentType, array $fields): void
    {

        $document = $this->buildDocument($id, $namespace, $documentType, $fields);

        $jsonDocument = json_encode($document);

        var_dump($this->config, "---------------------------------------------------------------------------------");
        var_dump($jsonDocument, "---------------------------------------------------------------------------------");

        $msg = new AMQPMessage(
            $jsonDocument,
            array(
                # make message persistent, so it is not lost if server crashes or quits
                'delivery_mode' => DataHubPublisher::DELIVERY_MODE
            )
        );

        //$this->channel->queue_declare($this->config->getQueueName(), false, false, true);
        $this->channel->basic_publish($msg, $this->config->getExchangeName(), $this->config->getRoutingKey());

    }

    /** Given an id, a namespace, a document type and a set of key value pairs, build a json
     *   representation of the document.
     *
     * @param String $id unique identifier of the document
     * @param string $namespace namespace under which the document will be stored in
     * @param string $documentType the structural type of the document, describing fields and their types.
     * @param array $fields fields as a key/value set
     * @return Document
     */
    public function buildDocument(string $id, string $namespace, string $documentType, array $fields): Document
    {

        $document = new Document(
            $id,
            DataHubConfig::prefixValue( $this->config->getPrefix(), $namespace ),
            $documentType,
            $fields
        );

        return $document;

    }


}