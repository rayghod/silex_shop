<?php
/**
 * This is the main project file. It defines all controllers, service providers 
 * and runs the application.
 *
 */


require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();
$app['debug'] = true;


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/Views',
));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());


$app->mount('/admin/', new Controller\AdminController());
$app->mount('/auth/', new Controller\AuthController());
$app->mount('/categories/', new Controller\CategoriesController());
$app->mount('/cart/', new Controller\OrdersController());
$app->mount('/products/', new Controller\ProductsController());
$app->mount('/register/', new Controller\RegisterController());
$app->mount('/search/', new Controller\SearchController());
$app->mount('/user/', new Controller\UsersController());
$app->mount('/', new Controller\IndexController());


$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => '127.0.0.1',
        'dbname'    => 'projekt_php',
        'user'      => 'root',
        'password'  => 'root',
        'charset'   => 'utf8',
    ),
));

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^.*$',
            'form' => array(
                'login_path' => '/auth/login',
                'check_path' => '/products/login_check',
                'default_target_path'=> '/products/',
                'username_parameter' => 'form[username]',
                'password_parameter' => 'form[password]',
            ),
            'logout'  => true,
            'anonymous' => true,
            'logout' => array('logout_path' => '/auth/logout'),
            'users' => $app->share(function() use ($app) {
                return new User\UserProvider($app);
            }),
        ),
    ),
    'security.access_rules' => array(
        array('^/auth.+$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
        array('^/register.*$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
        array('^/registrer/#*$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
        array('^/search*$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
        array('^/products/add$', 'ROLE_ADMIN'),
        array('^/admin$', 'ROLE_ADMIN'),
        array('^/products/edit.*$', 'ROLE_ADMIN'),
        array('^/products/delete$', 'ROLE_ADMIN'),
        array('^/categories.*$', 'ROLE_ADMIN'),
        array('^/cart$', 'ROLE_USER'),
        array('^/cart.*$', 'ROLE_USER'),
        array('^.*$', 'ROLE_USER')
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
));


$app->run();