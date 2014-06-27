<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

use Model\UsersModel;

class UsersController implements ControllerProviderInterface
{
    
public function connect(Application $app)
    {
        $usersController = $app['controllers_factory'];
        $usersController->match('/', array($this, 'index'))->bind('/user/');
        $usersController->match('/edit', array($this, 'edit'))->bind('/user/edit');
        return $usersController;
    }

    public function index(Application $app, Request $request)
    {
        
        $userModel = new UsersModel($app);
        $login= $app['security']->getToken()->getUser()->getUsername();
        $user = $userModel->getUserByLogin($login);
        
        return $app['twig']->render('users/info.twig', array('user' => $user));
    }


    public function edit(Application $app, Request $request)
    {        
        $login = $app['security']->getToken()->getUser()->getUsername();

        $usersModel = new UsersModel($app);
        $user = $usersModel->getUserByLogin($login);

        if (count($user)) { 
            $form = $app['form.factory']->createBuilder('form', $user)              
                ->add('login', 'text', array(
                    'constraints' => array(new Assert\NotBlank())
                ))
                ->add('password', 'password', array(
                    'constraints' => array(new Assert\NotBlank())           
                ))
                ->add('confirm_password', 'password', array(
                    'constraints' => array(new Assert\NotBlank())
                ))
                ->add('firstname', 'text', array(
                   'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                ))
                ->add('lastname', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                ))               
                ->add('phone_number', 'text', array(
                    'constraints' => array(new Assert\NotBlank())   
                ))
                ->add('street', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                ))
                ->add('email', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Email())
                ))
                ->add('house_number', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                ))
                ->add('postal_code', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                ))
                ->add('city', 'text', array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                ))
                ->add('save', 'submit')
                ->getForm();
 
            $form->handleRequest($request);

            if ($form->isValid()) {

                return $app['twig']->render('users/info.twig', array('user' => $user));
            }

            return $app['twig']->render('users/edit.twig', array('form' => $form->createView(), 'order' => $order));

        }
        else {
            return $app->redirect($app['url_generator']->generate('/finish/'), 301);
        }
    }
}