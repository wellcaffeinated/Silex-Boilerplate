<?php 

require_once __DIR__.'/vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app['debug'] = true;

if (!$app['debug']){
    error_reporting(0);
} else {
    error_reporting(E_ERROR);
    ini_set('display_errors', '1');
}

define('APP_DIR', __DIR__ . '/app');
define('ASSETS_DIR', __DIR__ . '/assets');

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => APP_DIR . '/logs/app.log',
    'monolog.level' => Monolog\Logger::DEBUG,
));

// email service provider
$app->register(new Silex\Provider\SwiftmailerServiceProvider(), array(
    'swiftmailer.options' => array(
        'host' => 'localhost',
        'port' => 25,
        'username' => 'noreply@example.com',
        'password' => '***',
        'auth_mode' => true
    ),
    // use sendmail transport
    // 'swiftmailer.transport' => new \Swift_SendmailTransport(),
));

// doctrine for db functions
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => APP_DIR . '/data/app.db',
    ),
));

// url service provider
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
// register the session extension
$app->register(new Silex\Provider\SessionServiceProvider(), array(
    'session.storage.options' => array(
        'secure' => true,
        'cookie_lifetime' => 60*60*24*7*25, // 25 weeks
    )
));
// register twig templating
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => array(
        ASSETS_DIR . '/templates'
    ),
    'twig.options' => array(
        'cache' => $app['debug'] ? null : ASSETS_DIR . '/cache/',
    ),
));

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addGlobal('lib_dir', $app['request']->getBaseUrl() . '/library');
    // $twig->addFilter('levenshtein', new \Twig_Filter_Function('levenshtein'));

    return $twig;
}));

// $app['security.firewalls'] = array(
//     'admin' => array(
//         'pattern' => '^/admin',
//         'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
//         'logout' => array('logout_path' => '/admin/logout'),
//         'users' => $app->share(function () use ($app) {
//             return new \Acme\Users\UserProvider($app['db'], $app['security.encoder_factory']);
//         }),
//         // 'users' => array(
//         //     'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
//         // ),
//     ),
// );

// $app->register(new Silex\Provider\SecurityServiceProvider());

$app->error(function (\Exception $e, $code) use ($app) {

    if ($app['debug']) return $e->getMessage();

    switch ($code) {

        case 404:
            $message = 'The requested page could not be found.';
            break;

        default:
            $message = 'We are sorry, but something went wrong. Please try again later.';
    }

    return $app['twig']->render('page-error.twig.html', array(
    
        'msg' => $message,
        'code' => $code,
    ));
});

$app->get('/', function(Request $request, Application $app){

    return $app['twig']->render('page-home.twig.html', array());
});

/**
 * Start App
 */
$app->run();

// end
