<?php

namespace Core;

class FaultTolerance {
    public function executeWithRetry($service, $action, $input, $retries = 3) {
        for ($i = 0; $i < $retries; $i++) {
            try {
                return $this->executor->executeService($service, $action, $input);
            } catch (\Exception $e) {
                $this->logFailure($e, $service, $action);
                if ($i === $retries - 1) {
                    throw $e;
                }
            }
        }
    }

    private function logFailure($e, $service, $action) {
        // Log failure to a file or service
    }
}
