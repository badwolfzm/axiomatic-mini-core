<?php

namespace Core;

class ServiceRegistry {
    private $services = [];

    public function registerService($name, $service) {
        $this->services[$name] = $service;
    }

    public function getService($name) {
        if (!isset($this->services[$name])) {
            throw new \Exception("Service not found: " . $name);
        }
        return $this->services[$name];
    }
}
