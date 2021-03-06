<?php
use Phalcon\Mvc\View;
use Phalcon\Crypt;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Files as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Logger\Formatter\Line as FormatterLine;
use Vokuro\Auth\Auth;
use Vokuro\Acl\Acl;
use Vokuro\Mail\Mail;

/**
 * Register the global configuration as config
 */
$di->setShared('config', function () {
    $config = include APP_PATH . '/config/config.php';
    
    if (is_readable(APP_PATH . '/config/config.dev.php')) {
        $override = include APP_PATH . '/config/config.dev.php';
        $config->merge($override);
    }
    
    return $config;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});

/**
 * Setting up the view component
 */
$di->set('view', function () {
    $config = $this->getConfig();

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir . 'volt/',
                'compiledSeparator' => '_'
            ]);

            return $volt;
        }
    ]);

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () {
    $config = $this->getConfig();
    return new DbAdapter([
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname
    ]);
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    $config = $this->getConfig();
    return new MetaDataAdapter([
        'metaDataDir' => $config->application->cacheDir . 'metaData/'
    ]);
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();
    return $session;
});

/**
 * Crypt service
 */
$di->set('crypt', function () {
    $config = $this->getConfig();

    $crypt = new Crypt();
    $crypt->setKey($config->application->cryptSalt);
    return $crypt;
});

/**
 * Dispatcher use a default namespace
 */
$di->set('dispatcher', function () {
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('Vokuro\Controllers');
    return $dispatcher;
});

/**
 * Loading routes from the routes.php file
 */
$di->set('router', function () {
    return require APP_PATH . '/config/routes.php';
});

/**
 * Flash service with custom CSS classes
 */
$di->set('flash', function () {
    return new Flash([
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
 * Custom authentication component
 */
$di->set('auth', function () {
    return new Auth();
});

/**
 * Mail service uses AmazonSES
 */
$di->set('mail', function () {
    return new Mail();
});

/**
 * Access Control List
 */
$di->set('acl', function () {
    return new Acl();
});

/**
 * Logger service
 */
$di->set('logger', function ($filename = null, $format = null) {
    $config = $this->getConfig();

    $format   = $format ?: $config->get('logger')->format;
    $filename = trim($filename ?: $config->get('logger')->filename, '\\/');
    $path     = rtrim($config->get('logger')->path, '\\/') . DIRECTORY_SEPARATOR;

    $formatter = new FormatterLine($format, $config->get('logger')->date);
    $logger    = new FileLogger($path . $filename);

    $logger->setFormatter($formatter);
    $logger->setLogLevel($config->get('logger')->logLevel);

    return $logger;
});


$di->setShared('ExampleService', [
    'className' => 'Vokuro\Examples\ExampleService',
    'arguments' => [
        [
            'type' => 'service',
            'name' => 'AService',
        ],
        [
            'type' => 'service',
            'name' => 'BService',
        ],
        [
            'type' => 'service',
            'name' => 'CService',
        ],
        [
            'type' => 'service',
            'name' => 'DService',
        ],
        [
            'type' => 'service',
            'name' => 'EService',
        ],
        [
            'type' => 'service',
            'name' => 'FService',
        ],
        [
            'type' => 'service',
            'name' => 'GService',
        ],
        [
            'type' => 'service',
            'name' => 'HService',
        ],
        [
            'type' => 'service',
            'name' => 'IService',
        ],
        [
            'type' => 'service',
            'name' => 'JService',
        ],
        [
            'type' => 'service',
            'name' => 'KService',
        ],
    ]
]);

$di->setShared('AService', [
    'className' => 'Vokuro\Examples\AService',
]);

$di->setShared('BService', [
    'className' => 'Vokuro\Examples\BService',
]);

$di->setShared('CService', [
    'className' => 'Vokuro\Examples\CService',
]);

$di->setShared('DService', [
    'className' => 'Vokuro\Examples\DService',
]);

$di->setShared('EService', [
    'className' => 'Vokuro\Examples\EService',
]);

$di->setShared('FService', [
    'className' => 'Vokuro\Examples\FService',
]);

$di->setShared('GService', [
    'className' => 'Vokuro\Examples\GService',
]);

$di->setShared('HService', [
    'className' => 'Vokuro\Examples\HService',
]);

$di->setShared('IService', [
    'className' => 'Vokuro\Examples\IService',
]);

$di->setShared('JService', [
    'className' => 'Vokuro\Examples\JService',
]);

$di->setShared('KService', [
    'className' => 'Vokuro\Examples\KService',
]);
