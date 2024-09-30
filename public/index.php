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

Autoloader::register();

$config = new Config();
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

$executor = new Executor($serviceRegistry);
$orchestrator = new Orchestrator($executor);
$parallelExecutor = new ParallelExecutor();
$faultTolerance = new FaultTolerance($executor);
$eventDispatcher = new EventDispatcher($orchestrator);
$security = new Security($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $workflow = $input['workflow'];

    if ($security->validateAccess($input['user'], $workflow['service'], $workflow['action'])) {
        $orchestrator->executeWorkflow($workflow, $input);
    } else {
        echo json_encode(['error' => 'Access denied']);
    }
}
