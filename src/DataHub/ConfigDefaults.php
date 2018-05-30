<?php
/**
 * Created by IntelliJ IDEA.
 * User: fsilva
 * Date: 5/28/18
 * Time: 11:26
 */

namespace We\DataHub;

/**
 * Class DataHubConfigDefaults holds default
 * @package We\DataHub
 */
class ConfigDefaults
{


    const DH_ES_HOSTS       = ["http://localhost:9200"];

    const DH_RMQ_HOST       = "localhost";

    const DH_RMQ_PORT       = 5672;

    const DH_RMQ_VHOST      = null;

    const DH_RMQ_USER       = "guest";

    const DH_RMQ_PASSWORD   = "guest";

    const DH_RMQ_QUEUE      = "datahub_queue";

    const DH_RMQ_EXCHANGE   = "datahub_exchange";

    const DH_RMQ_ROUTINGKEY = "redt.datahub.document";

    const DH_NS_PREFIX      = null;



}