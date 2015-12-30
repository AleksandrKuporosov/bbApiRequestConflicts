<?php

namespace bbApiRequestConflicts;

use GuzzleHttp\Client;

/**
 * Class Conflicts
 * @package bbApiRequestConflicts
 */
class Conflicts
{
    /**
     * API endpoint
     * @var string
     */
    private $endpoint;
    /**
     * Guzzle client
     * @var Client
     */
    private $client;
    /**
     * State name
     * @var string
     */
    private $state;
    /**
     * Check requests only from current login
     * @var bool
     */
    private $checkLogin;
    /**
     * User login
     * @var string
     */
    private $login;

    /**
     * Conflicts constructor.
     * @param $config []
     * @throws Exception
     */
    public function __construct($config)
    {
        if (!is_array($config)) {
            throw new Exception('Config must be an array');
        }

        $config = array_merge($this->defaultConfig(), $config);
        $required = $this->requiredConfig();

        foreach ($required as $option) {
            if (!isset($config[$option])) {
                throw new Exception("{$option} is required in config");
            }
        }

        $this->init($config);
    }

    /**
     * Init properties
     * @param $config
     */
    protected function init($config)
    {
        $this->client = new Client([
            'auth' => [
                $config['login'], $config['password'],
            ]
        ]);

        $this->endpoint = implode('/', [
            $config['apiURL'], 'repositories', $config['owner'], $config['slug'], 'pullrequests'
        ]);

        if (isset($config['state'])) {
            $this->state = $config['state'];
        }

        $this->login = $config['login'];
        $this->checkLogin = (bool) $config['checkLogin'];
    }

    /**
     * Default config values
     * @return array
     */
    protected function defaultConfig()
    {
        return [
            'apiURL' => 'https://api.bitbucket.org/2.0',
            'checkLogin' => true,
        ];
    }

    /**
     * Required config values
     * @return array
     */
    protected function requiredConfig()
    {
        return [
            'apiURL',
            'login',
            'password',
            'owner',
            'slug',
        ];
    }

    /**
     * Check if there conflicts in request
     * @param $requestId
     * @return int
     */
    protected function isConflicts($requestId)
    {
        $diff = $this->client->get($this->endpoint . '/' . $requestId . '/diff')->getBody();
        return stripos($diff, '+<<<<<<< destination:');
    }

    /**
     * List of requests links with conflicts
     * @return array
     */
    public function getLinks()
    {
        $links = [];

        $url = $this->endpoint . ($this->state ? '?state=' . $this->state : '');
        do {
            $response = $this->client->get($url);
            $result = json_decode($response->getBody(), true);
            $url = isset($result['next']) ? $result['next'] : null;

            $requests = $result['values'];
            foreach ($requests as $request) {
                if ($this->checkLogin
                    && $this->login != $request['author']['username']) {
                    continue;
                }

                if ($this->isConflicts($requests['id'])) {
                    $links[] = $request['links']['html']['href'];
                }
            }
        } while ($url);

        return $links;
    }
}