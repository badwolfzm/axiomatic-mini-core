<?php

namespace Core;

use Symfony\Component\Yaml\Yaml;

class PluginLoader {
    private $plugins = [];

    public function loadPlugins($pluginDir = __DIR__ . '/../../plugins/') {
        foreach (glob($pluginDir . "*.yaml") as $file) {
            $plugin = Yaml::parseFile($file);
            $this->plugins[] = $plugin;
        }
    }

    public function getPlugins() {
        return $this->plugins;
    }
}
