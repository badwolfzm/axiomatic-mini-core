<?php

namespace Core;

use Symfony\Component\Yaml\Yaml;

class Config {
    private $config;

    public function __construct($configPath = __DIR__ . '/../../config/config.yaml') {
        $this->config = Yaml::parseFile($configPath);
    }

    public function get($key, $default = null) {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $segment) {
            if (isset($value[$segment])) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }

        return $value;
    }
}
