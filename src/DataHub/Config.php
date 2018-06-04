<?php
/**
 * Created by IntelliJ IDEA.
 * User: fsilva
 * Date: 5/21/18
 * Time: 12:20
 */

namespace We\DataHub;

/**
 * Holds configuration for the `Publisher`
 *
 * Also defines a static helper to create an instance from configuration variables.
 *
 * @package We\DataHub
 */
class Config
{

    /**
     * Creates an instance of `Config` with values gathered from the standard DataHub environment variables
     *  with fallback to `ConfigDefaults`.
     *
     * @return Config resolved configuration
     */
    public static function resolveConfigFromEnvironment(): Config {

        $esHostsEnv = getenv("DH_ES_HOSTS");
        $esHosts    = empty($esHostsEnv) ? ConfigDefaults::DH_ES_HOSTS : mb_split($esHostsEnv, ",");

        $host       = env("DH_RMQ_HOST",        ConfigDefaults::DH_RMQ_HOST);
        $port       = env("DH_RMQ_PORT",        ConfigDefaults::DH_RMQ_PORT);
        $vhost      = getenv("DH_RMQ_VHOST");
        $user       = env("DH_RMQ_USER",        ConfigDefaults::DH_RMQ_USER);
        $pass       = env("DH_RMQ_PASSWORD",    ConfigDefaults::DH_RMQ_PASSWORD);
        $queue      = env("DH_RMQ_QUEUE",       ConfigDefaults::DH_RMQ_QUEUE);
        $exchange   = env("DH_RMQ_EXCHANGE",    ConfigDefaults::DH_RMQ_EXCHANGE);
        $routingKey = env("DH_RMQ_ROUTINGKEY",  ConfigDefaults::DH_RMQ_ROUTINGKEY);
        $prefix     = getenv("DH_NS_PREFIX");

        return new Config(
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

    /** Static helper to prepend prefixes in a standard way.
     *
     * If no prefix is passed, the input value is returned as is.
     *
     * @param string $prefix
     * @param string $value
     * @return string
     *
     */
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

    /**
     * Config constructor, takes care of prefixing the appropriate members (queue, exchange, routing key).
     *
     * @param array $esHosts
     * @param string $host
     * @param int $port
     * @param string $password
     * @param string $user
     * @param string $vhost
     * @param string $queueName
     * @param string $exchangeName
     * @param string $routingKey
     * @param string $prefix
     */
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
        return Config::prefixValue($this->prefix, $value);
    }

}