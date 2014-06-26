<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

use Model\UsersModel;
use Model\OrdersModel;

class OrdersController implements ControllerProviderInterface
{
    public function getCurrentUserLogin(Application $app)
    {
        $user = $app['security']->getToken()->getUser()->getUsername();
        return $user;
    }

    public function connect(Application $app)
    {
        $ordersController = $app['controllers_factory'];
        $ordersController->match('/', array($this, 'index'))->bind('/cart/');
        $ordersController->match('/add/{id}', array($this, 'add'))->bind('/cart/add');
        $ordersController->match('/delete/{id}', array($this, 'delete'))->bind('/products/delete');
        return $ordersController;
    }

    public function index(Application $app)
    {
        $user = $app['security']->getToken()->getUser()->getUsername();

        $ordersModel = new OrdersModel($app);
        $cart = $ordersModel->getCart($user);
        return $app['twig']->render('orders/cart.twig', array('products' => $cart));

    }

    public function add(Application $app, Request $request)
    {
        $idProduct = (int) $request->get('id', 0);
        $login = $app['security']->getToken()->getUser()->getUsername();

        $ordersModel = new OrdersModel($app);
        $order = $ordersModel->getOrder($login);
        $ordersModel->addToCart($idProduct, $order['id']);

        return $app->redirect($app['url_generator']->generate('/cart/'), 301);
    }

    public function delete(Application $app, Request $request)
    {
        $idProduct = (int) $request->get('id', 0);
        $login = $app['security']->getToken()->getUser()->getUsername();

        $ordersModel = new OrdersModel($app);
        $order = $ordersModel->getOrder($login);
        $ordersModel->removeFromCart($idProduct, $order['id']);

        return $app->redirect($app['url_generator']->generate('/cart/'), 301);
    }
}