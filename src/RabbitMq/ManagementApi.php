<?php

namespace Endeavor\RabbitMq;

use Endeavor\Util\JSON;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ManagementApi
 */
class ManagementApi
{
    /**
     * Config with HTTP API credentials
     *
     * @var array
     */
    protected $config;

    /**
     * ManagementApi constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
            'url' => 'http://localhost:15672/api/',
            'user' => 'guest',
            'pass' => 'guest',
        ]);

        $this->config = $resolver->resolve($config);
    }

    /**
     * @return string
     */
    public function getOverview()
    {
        $json = $this->getInfo('overview');

        return JSON::encode($json);
    }

    /**
     * @param $vhost
     * @param $queueName
     * @return array|object
     */
    public function getQueue($vhost, $queueName)
    {
        $json = $this->getInfo('queues/' . urlencode($vhost) . '/' . urlencode($queueName));
        $result = JSON::decode($json);

        if (array_key_exists('error', $result)) {
            $result = [
                'messages' => 0,
                'consumer_details' => [],
            ];
        }
        return $result;
    }

    /**
     * @param string $path
     *
     * @return mixed
     */
    protected function getInfo($path = 'overview')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->config['user']}:{$this->config['pass']}");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90);

        curl_setopt($ch, CURLOPT_URL, $this->config['url'] . $path);

        return curl_exec($ch);
    }
}