<?php

namespace Core;

class TranslationManager {
    private $translations;

    public function getTranslation($service, $version = 'latest') {
        if ($version === 'latest') {
            return end($this->translations[$service]);
        }
        return $this->translations[$service][$version] ?? null;
    }

    public function storeTranslation($service, $version, $steps) {
        $this->translations[$service][$version] = $steps;
    }
}
