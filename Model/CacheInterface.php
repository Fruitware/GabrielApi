<?php

namespace Fruitware\GabrielApi\Model;

interface CacheInterface
{
    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     *
     * @return mixed|null
     */
    public function set($key, $value, $minutes);

    /**
     * Retrieve an item from the cache by key.
     *
     * @param $key
     *
     * @return mixed|null
     */
    public function get($key);

    /**
     * Remove an item from the cache.
     *
     * @param $key
     *
     * @return mixed| null
     */
    public function delete($key);
}