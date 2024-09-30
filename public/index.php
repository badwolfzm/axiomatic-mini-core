<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Core/Autoloader.php';

use Core\Autoloader;
use Core\Config;
use Core\PluginLoader;
use Core\ServiceRegistry;
use Core\Executor;
use Core\Orchestrator;
use Core\FaultTolerance;
use Core\EventDispatcher;
use Core\ParallelExecutor;
use Core\Security;
use Core\Logger; // Make sure you import the Logger class

Autoloader::register();

// Load the configuration
$config = new Config();

// Set up service registry and plugin loader
$serviceRegistry = new ServiceRegistry();
$pluginLoader = new PluginLoader();
$pluginLoader->loadPlugins();
$plugins = $pluginLoader->getPlugins();

foreach ($plugins as $plugin) {
    foreach ($plugin['plugin']['services'] as $service) {
        $class = $service['class'];
        $serviceInstance = new $class();
        $serviceRegistry->registerService($service['name'], $serviceInstance);
    }
}

// Set up the logger
$logger = new Logger($config->get('logging'));

// Set up the executor and orchestrator
$executor = new Executor($serviceRegistry);
$orchestrator = new Orchestrator($executor, $logger); // Pass both Executor and Logger

$parallelExecutor = new ParallelExecutor();
$faultTolerance = new FaultTolerance($executor);
$eventDispatcher = new EventDispatcher($orchestrator);
$security = new Security($config);

// Your request handling logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $workflow = $input['workflow'];

    try {
        // Validate input fields (if needed)
        // Execute workflow
        if ($security->validateAccess($input['user'], $workflow['service'], $workflow['action'])) {
            $orchestrator->executeWorkflow($workflow, $input);
        } else {
            echo json_encode(['error' => 'Access denied']);
        }
    } catch (\Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        error_log("Error: " . $e->getMessage());
    }
}
