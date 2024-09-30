<?php

namespace Core;

class ParallelExecutor {
    public function executeInParallel($services, $input) {
        $results = [];
        foreach ($services as $service) {
            $results[] = $this->executeServiceAsync($service, $input);
        }
        return $this->gatherResults($results);
    }

    private function executeServiceAsync($service, $input) {
        // Async execution logic
        return $this->executor->executeService($service, 'action', $input);
    }

    private function gatherResults($results) {
        return array_map(function ($result) {
            return $result['status'];
        }, $results);
    }
}
