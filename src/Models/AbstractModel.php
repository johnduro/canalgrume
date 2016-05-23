<?php

namespace Models;


abstract class AbstractModel
{
    protected $_metadata;

    public function __construct()
    {
        $this->loadMetadata();
    }

    abstract protected function loadMetadata();

    public function checkKeyExistInMetadata($key)
    {
        if (!isset($this->_metadata[$key])) {
            throw new \Exception("Mapping error : key '". $key ."' doesn't exist for " . get_class($this), 1);
        }
    }

    public function keyIsAssociatedObject($key)
    {
        $this->checkKeyExistInMetadata($key);
        if (is_array($this->_metadata[$key])) {
            if ($this->_metadata[$key][0] !== 'model' && $this->_metadata[$key][0] !== 'collection') {
                throw new \Exception('Mapping error: expecting "model" or "collection" for first entry ' . $this->_metadata[$key][0] . $key, 1);
            }
            return true;
        }
        return false;
    }

    public function keyIsModel($key)
    {
        if (!$this->keyIsAssociatedObject($key)) {
            return false;
        }
        return $this->_metadata[$key][0] === 'model';
    }

    public function keyIsCollection($key)
    {
        if (!$this->keyIsAssociatedObject($key)) {
            return false;
        }
        return $this->_metadata[$key][0] === 'collection';
    }

    public function getEntityName($key)
    {
        if (!$this->keyIsAssociatedObject()) {
            throw new \Exception("Tring to get entity name from an non associated object for " . get_class($this), 1);
        };
        return $this->_metadata[$key][1];
    }
}
