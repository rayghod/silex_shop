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
        $order = $ordersModel->getCart($user);
        return $app['twig']->render('orders/orders.twig', array('orders' => $order));
    }
}