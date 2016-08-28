<?php
/*
 MIT License
 Copyright (c) 2010 - 2015 Daniel Hoffend, Peter Petermann

 Permission is hereby granted, free of charge, to any person
 obtaining a copy of this software and associated documentation
 files (the "Software"), to deal in the Software without
 restriction, including without limitation the rights to use,
 copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the
 Software is furnished to do so, subject to the following
 conditions:

 The above copyright notice and this permission notice shall be
 included in all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 OTHER DEALINGS IN THE SOFTWARE.
*/

namespace Pheal\Cache;

/**
 * Implememnts memcached into Pheal
 */
class MemcacheStorage extends MemcacheBase implements CanCache
{
    /**
     * Active memcache instance/connection
     *
     * @var \Memcache
     */
    protected $memcache;

    /**
     * Initialise memcache storage cache.
     *
     * @param array $options optional config array, valid keys are: host, port
     */
    public function __construct(array $options = array())
    {
        $this->options = $options + $this->options;
        $this->memcache = new \Memcache();
        $this->memcache->connect($this->options['host'], $this->options['port']);
    }

    /**
     * Load XML from cache
     *
     * @param int $userid
     * @param string $apikey
     * @param string $scope
     * @param string $name
     * @param array $args
     * @return string
     */
    public function load($userid, $apikey, $scope, $name, $args)
    {
        $key = $this->getKey($userid, $apikey, $scope, $name, $args);
        return (string) $this->memcache->get($key);
    }

    /**
     * Save XML to cache
     *
     * @param int $userid
     * @param string $apikey
     * @param string $scope
     * @param string $name
     * @param array $args
     * @param string $xml
     * @return bool|void
     */
    public function save($userid, $apikey, $scope, $name, $args, $xml)
    {
        $key = $this->getKey($userid, $apikey, $scope, $name, $args);
        $timeout = $this->getTimeout($xml);
        $this->memcache->set($key, $xml, 0, time() + $timeout);
    }
}
