<?php

namespace Illuminate\Cache;

use Illuminate\Contracts\Cache\Store;
use Momento\Auth\EnvMomentoTokenProvider;
use Momento\Cache\Errors\UnknownError;
use Momento\Cache\SimpleCacheClient;

class MomentoStore implements Store
{
    protected SimpleCacheClient $client;
    protected string $cacheName;

    public function __construct(string $cacheName, int $defaultTtl)
    {
        $authProvider = new EnvMomentoTokenProvider('MOMENTO_AUTH_TOKEN');
        $this->client = new SimpleCacheClient($authProvider, $defaultTtl);
        $this->cacheName = $cacheName;
        $this->client->createCache($cacheName);
    }

    public function get($key)
    {
        $result = $this->client->get($this->cacheName, $key);
        if ($result->asHit()) {
            return $result->asHit()->value();
        } elseif ($result->asMiss()) {
            return null;
        }
    }

    public function many(array $keys)
    {
        throw new UnknownError("many operations is currently not supported.");
    }

    public function put($key, $value, $seconds)
    {
        $result = $this->client->set($this->cacheName, $key, $value, $seconds);
        if ($result->asSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    public function putMany(array $values, $seconds)
    {
        throw new UnknownError("putMany operations is currently not supported.");
    }

    public function increment($key, $value = 1)
    {
        return true;
//        throw new UnknownError("increment operations is currently not supported.");
    }

    public function decrement($key, $value = 1)
    {
        throw new UnknownError("decrement operations is currently not supported.");
    }

    public function forever($key, $value)
    {
        throw new UnknownError("forever operations is currently not supported.");
    }

    public function forget($key)
    {
        throw new UnknownError("forget operations is currently not supported.");
    }

    public function flush()
    {
        throw new UnknownError("flush operations is currently not supported.");
    }

    public function getPrefix()
    {
    }
}
