<?php
namespace App\Services;

class CacheService {

    protected $cache;

    public function __construct($cache) {
        $this->cache = $cache;
    }

    /**
  	* Get Item from cache
  	* @return array/boolean
  	*/
    public function getItemFromCache($id){

      if ($this->cache->exists($id)) {
          return unserialize($this->cache->get($id));
      }
      return false;
    }

    /**
  	* Get Item from cache
  	* @return array
  	*/
    public function saveItemInCache($id, $value, $ttl = 1500){
      $serializedValue = serialize($value);
      return $this->cache->set($id, $serializedValue)
            and $this->cache->expire($id, $ttl);
    }

}
