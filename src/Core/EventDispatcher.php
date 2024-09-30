<?php

namespace Core;

class EventDispatcher {
    private $orchestrator;

    public function __construct($orchestrator) {
        $this->orchestrator = $orchestrator;
    }

    public function dispatch($event, $input) {
        $workflow = $this->getWorkflowForEvent($event);
        $this->orchestrator->executeWorkflow($workflow, $input);
    }

    private function getWorkflowForEvent($event) {
        return [
            'steps' => [
                ['service' => 'PrepareMessageService', 'action' => 'prepare'],
                ['service' => 'SendEmailService', 'action' => 'send'],
                ['service' => 'LogService', 'action' => 'log'],
            ],
        ];
    }
}
