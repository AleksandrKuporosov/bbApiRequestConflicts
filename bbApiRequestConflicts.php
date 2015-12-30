<?php

namespace bbApiRequestConflicts;

use GuzzleHttp\Client;

class bbApiRequestConflicts
{
    private $endpoint;
    private $client;
    private $state;
    private $checkLogin;
    private $login;

    public function __construct($config)
    {
        if (!is_array($config)) {
            throw new bbApiRequestConflictsException('Config must be an array');
        }

        $config = array_merge($this->defaultConfig(), $config);
        $required = $this->requiredConfig();

        foreach ($required as $option) {
            if (!isset($config[$option])) {
                throw new bbApiRequestConflictsException("{$option} is required in config");
            }
        }

        $this->init($config);
    }

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
        $this->checkLogin = isset($config['checkLogin']) ? (bool) $config['checkLogin'] : true;
    }

    protected function defaultConfig()
    {
        return [
            'apiURL' => 'https://api.bitbucket.org/2.0',
        ];
    }

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

    public function getConflicts()
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
                    && $this->login !== $request['author']['username']) {
                    continue;
                }

                $diff = $this->client->get($this->endpoint . '/' . $request['id'] . '/diff')->getBody();
                if (stripos($diff, '+<<<<<<< destination:')) {
                    $links[] = $request['links']['html']['href'];
                }
            }
        } while ($url);

        return $links;
    }
}