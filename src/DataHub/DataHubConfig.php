<?php
/**
 * Created by IntelliJ IDEA.
 * User: fsilva
 * Date: 5/21/18
 * Time: 12:20
 */

namespace We\DataHub;

class DataHubConfig
{

    public static function resolveEnvironmentConfig(): DataHubConfig {

        $esHostsEnv = getenv("DH_ES_HOSTS");
        $esHosts    = empty($esHostsEnv) ? DataHubConfigDefaults::DH_ES_HOSTS : mb_split($esHostsEnv, ",");

        $host       = env("DH_RMQ_HOST",        DataHubConfigDefaults::DH_RMQ_HOST);
        $port       = env("DH_RMQ_PORT",        DataHubConfigDefaults::DH_RMQ_PORT);
        $vhost      = getenv("DH_RMQ_VHOST");
        $user       = env("DH_RMQ_USER",        DataHubConfigDefaults::DH_RMQ_USER);
        $pass       = env("DH_RMQ_PASSWORD",    DataHubConfigDefaults::DH_RMQ_PASSWORD);
        $queue      = env("DH_RMQ_QUEUE",       DataHubConfigDefaults::DH_RMQ_QUEUE);
        $exchange   = env("DH_RMQ_EXCHANGE",    DataHubConfigDefaults::DH_RMQ_EXCHANGE);
        $routingKey = env("DH_RMQ_ROUTINGKEY",  DataHubConfigDefaults::DH_RMQ_ROUTINGKEY);
        $prefix     = getenv("DH_NS_PREFIX");

        return new DataHubConfig(
            $esHosts,
            $host,
            $port,
            $user,
            $pass,
            $vhost,
            $queue,
            $exchange,
            $routingKey,
            $prefix
        );

    }

    public static function prefixValue(string $prefix, string $value) {
        if( ! empty($prefix) ) {
            return $prefix . "_" . $value;
        }
        return $value;
    }

    private $esHosts;

    private $host;

    private $port;

    private $user;

    private $password;

    private $vhost;

    private $queueName;

    private $exchangeName;

    private $routingKey;

    private $prefix;

    public function __construct(
        array   $esHosts,
        string  $host,
        int     $port,
        string  $password,
        string  $user,
        string  $vhost ,
        string  $queueName,
        string  $exchangeName,
        string  $routingKey,
        string  $prefix )
    {
        $this->prefix = $prefix;
        $this->esHosts  = $esHosts;
        $this->host     = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->vhost = $vhost;
        $this->queueName = $this->prefix($queueName);
        $this->exchangeName = $this->prefix($exchangeName);
        $this->routingKey = $this->prefix($routingKey);
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getVhost()
    {
        return $this->vhost;
    }

    /**
     * @return mixed
     */
    public function getQueueName()
    {
        return $this->queueName;
    }

    /**
     * @return mixed
     */
    public function getExchangeName()
    {
        return $this->exchangeName;
    }

    /**
     * @return mixed
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return string[]
     */
    public function getEsHosts()
    {
        return $this->esHosts;
    }

    /** If we have a prefix, ... prefix it to the passed value.
     * @param string $value value to apply prefix to.
     * @return string the prefixed value if there was a prefix, the original value otherwise.
     */
    private function prefix(string $value) {
        return DataHubConfig::prefixValue($this->prefix, $value);
    }

}