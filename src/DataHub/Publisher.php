<?php

namespace We\DataHub;

use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Publisher {

    /** @var AMQPStreamConnection  */
    private $connection;

    /** @var \PhpAmqpLib\Channel\AMQPChannel  */
    private $channel;

    /**
     * @var Config
     */
    private $config;

    const DELIVERY_MODE = 2;

    public function __construct(Config $config)
    {

        $this->config = $config;

        //Establish connection to AMQP
        // FIXME I'm covering my butt by asking for null here, but maybe the client handles this ok. Try it out.
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
     *  Encodes fields, creates document and sends it to the `DataHub` via the configured queue.
     *
     * @param string $id the id of the document to index, will be used as id for the document
     * @param string $namespace namespace to put this document into, must not have uppercase characters.
     * @param string $documentType type of this document, must not have uppercase characters.
     * @param Fields $fields the list of key value pairs, representing the fields
     */
    public function sendDocument(string $id, string $namespace, string $documentType, Fields $fields): void
    {

        $document = $this->buildDocument($id, $namespace, $documentType, $fields);

        $jsonDocument = json_encode($document);

        $msg = new AMQPMessage(
            $jsonDocument,
            array(
                # make message persistent, so it is not lost if server crashes or quits
                'delivery_mode' => Publisher::DELIVERY_MODE
            )
        );

        //
        // Declaring the queue here makes rmq complain that:
        //  PRECONDITION_FAILED - inequivalent arg 'durable' for queue 'datahub_queue' in vhost '/': received 'false' but current is 'true'
        // which seems to be not true, by looking at the admin, but not doing this just works, so ...
        //
        // $this->channel->queue_declare($this->config->getQueueName(), false, false, true);
        //
        $this->channel->basic_publish($msg, $this->config->getExchangeName(), $this->config->getRoutingKey());

    }

    /**
     * Given an id, a namespace, a document type and a set of key value pairs, build a json
     *   representation of the document.
     *
     * @param String $id unique identifier of the document
     * @param string $namespace namespace under which the document will be stored in
     * @param string $documentType the structural type of the document, describing fields and their types.
     * @param array $fields fields as a key/value set
     * @return Document
     *
     * @throws Exception when a namespace or a document type have uppercase characters.
     */
    public function buildDocument(string $id, string $namespace, string $documentType, Fields $fields): Document
    {

        $document = new Document(
            $id,
            Config::prefixValue( $this->config->getPrefix(), $namespace ),
            $documentType,
            $fields
        );

        return $document;

    }


}