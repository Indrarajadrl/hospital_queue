<?php
namespace Khansia\Access\Session\SaveHandler;

class Cache extends \Zend\Session\SaveHandler\Cache {

    protected $owner = null;

    public function setOwner($owner) {

        $this->owner = $owner;
    }

    public function getOwner() {

        return $this->owner;
    }
}