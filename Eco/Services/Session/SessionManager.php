<?php

namespace ScoobEco\Eco\Services\Session;

use Redis;
use ScoobEco\Core\Config;

class SessionManager
{
    private $redis;
    private $sessionPrefix = 'session:';
    private $sessionTTL    = 3600;

    public function __construct()
    {
        $host        = Config::get('database.connections.redis.host');
        $port        = Config::get('database.connections.redis.port');
        $pass        = Config::get('database.connections.redis.pass');
        $this->redis = new Redis();
        $this->redis->connect($host, $port);

        if (!empty($pass)) {
            $this->redis->auth($pass);
        }
    }

    private function generateToken()
    {
        return bin2hex(random_bytes(32));
    }

    public function createSession(
        array|object $userData,
        string|null  $key = null,
    )
    {
        $token = $this->generateToken();

        if ($key) {
            $key = $this->sessionPrefix . $key;
        }

        if (!$key) {
            $key = $this->sessionPrefix . $token;
        }

        $this->redis->setex($key, $this->sessionTTL, json_encode($userData));

        return $token;
    }

    public function getSession(string $token)
    {
        $key  = $this->sessionPrefix . $token;
        $data = $this->redis->get($key);

        if (!$data) {
            return null;
        }

        $this->redis->expire($key, $this->sessionTTL);

        return json_decode($data, true);
    }

    public function destroySession(string $token)
    {
        $key = $this->sessionPrefix . $token;
        return $this->redis->del($key);
    }

    public function listSessions(): array
    {
        $keys     = $this->redis->keys($this->sessionPrefix . '*');
        $sessions = [];

        foreach ($keys as $key) {
            $token      = str_replace($this->sessionPrefix, '', $key);
            $data       = $this->redis->get($key);
            $sessions[] = [
                'token' => $token,
                'data'  => json_decode($data, true)
            ];
        }

        return $sessions;
    }

    public function clearAllSessions(): int
    {
        $keys = $this->redis->keys($this->sessionPrefix . '*');

        if (empty($keys)) {
            return 0;
        }

        return $this->redis->del(...$keys);
    }
}
