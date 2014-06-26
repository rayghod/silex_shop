<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Model\UsersModel;

class AuthController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $authController = $app['controllers_factory'];
        $authController->match('/login', array($this, 'login'))->bind('/auth/login');
        $authController->match('/logout', array($this, 'logout'))->bind('/auth/logout');
        return $authController;
    }

    public function login(Application $app, Request $request)
    {
        $data = array();

        $form = $app['form.factory']->createBuilder('form')
            ->add('username', 'text', array('label' => 'Username','data' => $app['session']->get('_security.last_username')))
            ->add('password', 'password', array('label' => 'Password'))
            ->add('login', 'submit')
            ->getForm();

        return $app['twig']->render('auth/login.twig', array(
            'form' => $form->createView(),
            'error' => $app['security.last_error']($request)
        ));
    }

    public function logout(Application $app, Request $request)
    {
        $app['session']->clear();
        return $app['twig']->render('auth/logout.twig');
    }

}