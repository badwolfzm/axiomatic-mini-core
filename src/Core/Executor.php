<?php

namespace Core;

class Executor {
    private $serviceRegistry;

    public function __construct(ServiceRegistry $serviceRegistry) {
        $this->serviceRegistry = $serviceRegistry;
    }

    public function executeService($serviceName, $action, $input) {
        $service = $this->serviceRegistry->getService($serviceName);

        if (!method_exists($service, $action)) {
            throw new \Exception("Action not found: " . $action . " in service " . $serviceName);
        }

        return $service->$action($input);
    }
}
