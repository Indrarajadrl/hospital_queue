<?php
namespace Khansia\Access\Session\Config;

class StandardConfig extends \Zend\Session\Config\StandardConfig {

    protected $lifetime = 86400;  /* 24 hrs */

    public function setLifetime($lifetime) {

        $this->lifetime = $lifetime;
    }

    public function getLifetime() {

        return $this->lifetime;
    }
}