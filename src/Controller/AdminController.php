<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;


use Model\OrdersModel;

class AdminController implements ControllerProviderInterface
{
	
	public function connect(Application $app)
    {
    	$adminController = $app['controllers_factory'];
        $adminController->match('/', array($this, 'index'))->bind('/admin');
        $adminController->match('/{id}', array($this, 'orders'))->bind('/admin/');
        return $adminController;
    }

    public function index(Application $app)
    {
    	$ordersModel = new OrdersModel($app);
    	$orders = $ordersModel->getFinishedOrders();
    	return $app['twig']->render('orders/orders.twig', array('orders' => $orders));
    }

    public function orders(Application $app, Request $request)
    {
    	$id = (int) $request->get('id', 0);
    	$ordersModel = new OrdersModel($app);
    	$orders = $ordersModel->getFinishedOrders();
    	$products = $ordersModel->getProductsFromOrder($id);
    	return $app['twig']->render('orders/orders-finished.twig', array('orders' => $orders, 'products' => $products));
    }
}