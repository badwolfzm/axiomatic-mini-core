<?php

namespace Core;

class FaultTolerance {
    private $logger;
    private $executor;

    public function __construct($executor, $logger) {
        $this->executor = $executor;
        $this->logger = $logger;
    }

    public function executeWithRetry($service, $action, $input, $retries = 3) {
        for ($i = 0; $i < $retries; $i++) {
            try {
                return $this->executor->executeService($service, $action, $input);
            } catch (\Exception $e) {
                $this->logFailure($e, $service, $action);
                if ($i === $retries - 1) {
                    throw $e; // Rethrow after final retry
                }
            }
        }
    }

    private function logFailure($e, $service, $action) {
        $this->logger->log("Failed to execute $action in $service after retry: " . $e->getMessage(), 'WARNING');
    }
}
