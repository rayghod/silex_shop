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
        $ordersController->match('/delete/{id}', array($this, 'delete'))->bind('/cart/delete');
        $ordersController->match('/finish', array($this, 'finish'))->bind('/cart/finish');
        $ordersController->match('/finish/completed', array($this, 'finish_completed'))->bind('/cart/finish/completed');
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

    public function finish(Application $app, Request $request)
    {
        $login = $app['security']->getToken()->getUser()->getUsername();
        $ordersModel = new OrdersModel($app);
        $order = $ordersModel->getOrder($login);
        
        if (count($order)) {

            $form = $app['form.factory']->createBuilder('form', $order)
                ->add(
                    'street', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                    )
                )
                ->add(
                    'house_number', 'text', array(
                    'constraints' => array(new Assert\NotBlank()))
                )
                ->add(
                    'postal_code', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                    )
                )
                ->add(
                    'city', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                    )
                )
                ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $data['street'] = $app->escape($data['street']);
                $data['house_number'] = $app->escape($data['house_number']);
                $data['postal_code'] = $app->escape($data['postal_code']);
                $data['city'] = $app->escape($data['city']);
                $login = $app['security']->getToken()->getUser()->getUsername();
                $ordersModel->finishOrder($data, $login);
                return $app->redirect($app['url_generator']->generate('/cart/finish/completed'), 301);
            }

            return $app['twig']->render('orders/finish.twig', array('form' => $form->createView(), 'order' => $order));

        } else {
            return $app->redirect($app['url_generator']->generate('/finish/'), 301);
        }

    }

    public function finish_completed(Application $app, Request $request)
    {
        return $app['twig']->render('orders/finish-completed.twig');
    }
}