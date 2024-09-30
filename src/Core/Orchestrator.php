<?php

namespace Core;

class Orchestrator {
    private $executor;

    public function __construct(Executor $executor) {
        $this->executor = $executor;
    }

    public function executeWorkflow($workflow, $input) {
        $steps = $workflow['steps'];
        $results = [];

        foreach ($steps as $step) {
            $serviceName = $step['service'];
            $action = $step['action'];

            try {
                $result = $this->executor->executeService($serviceName, $action, $input);
                $results[$serviceName] = $result;
            } catch (\Exception $e) {
                $this->handleError($e, $serviceName, $action);
            }
        }

        return $results;
    }

    private function handleError($exception, $serviceName, $action) {
        // Handle errors during the execution of services
        // Log the error or perform fallback actions as needed
        error_log("Error executing $action on $serviceName: " . $exception->getMessage());
    }
}
