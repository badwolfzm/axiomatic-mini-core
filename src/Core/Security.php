<?php

namespace Core;

class Security {
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function validateAccess($user, $service, $action) {
        $roles = $this->config->get('security.roles');
        $permissions = $this->config->get('security.permissions');
        $userRole = $this->getUserRole($user);

        return isset($permissions[$userRole][$service]) &&
               (in_array($action, $permissions[$userRole][$service]) || $permissions[$userRole][$service] === '*');
    }

    private function getUserRole($user) {
        return 'user';
    }
}
