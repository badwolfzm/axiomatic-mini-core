<?php

namespace Core;

class Orchestrator {
    private $executor;
    private $logger;

    public function __construct(Executor $executor, Logger $logger) {
        $this->executor = $executor;
        $this->logger = $logger;
    }

    public function executeWorkflow($workflow, $input) {
        $steps = $workflow['steps'];
        $results = [];

        foreach ($steps as $step) {
            $serviceName = $step['service'];
            $action = $step['action'];

            try {
                $result = $this->executor->executeService($serviceName, $action, $input);
                $this->logger->log("Executed $action in $serviceName successfully", 'INFO');
                $results[$serviceName] = $result;
            } catch (\Exception $e) {
                $this->handleError($e, $serviceName, $action);
            }
        }

        return $results;
    }

    private function handleError($exception, $serviceName, $action) {
        $this->logger->log("Error executing $action on $serviceName: " . $exception->getMessage(), 'ERROR');
        error_log("Error executing $action on $serviceName: " . $exception->getMessage());
    }
}
