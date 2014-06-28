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

        
        $form = $app['form.factory']->createBuilder('form', $user)              
            ->add(
                'login', 'text', array(
                'constraints' => array(new Assert\NotBlank())
                )
            )
            ->add(
                'password', 'password', array(
                'constraints' => array(new Assert\NotBlank())           
                )
            )
            ->add(
                'confirm_password', 'password', array(
                'constraints' => array(new Assert\NotBlank())
                )
            )
            ->add(
                'firstname', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                )
            )
            ->add(
                'lastname', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                )
            )               
            ->add(
                'phone_number', 'text', array(
                'constraints' => array(new Assert\NotBlank())   
                )
            )
            ->add(
                'street', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                )
            )
            ->add(
                'email', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Email())
                )
            )
            ->add(
                'house_number', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                )
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

            $data['login'] = $app->escape($data['login']);
            $data['password'] = $app->escape($data['password']);
            $data['confirm_password'] = $app->escape($data['confirm_password']);
            $data['firstname'] = $app->escape($data['firstname']);
            $data['lastname'] = $app->escape($data['lastname']);
            $data['phone_number'] = $app->escape($data['phone_number']);
            $data['street'] = $app->escape($data['street']);
            $data['email'] = $app->escape($data['email']);
            $data['house_number'] = $app->escape($data['house_number']);
            $data['postal_code'] = $app->escape($data['postal_code']);
            $data['city'] = $app->escape($data['name']);
            
            if ($data['password'] === $data['confirm_password']) {
              
                $encodedPassword = $app['security.encoder.digest']->encodePassword($data['password'], '');
                
                $usersModel = new UsersModel($app);
                $checkLogin = $usersModel->getUserByLogin($data['login']);
                if ($data['login'] === $login || !$checkLogin) { 
                    $usersModel->updateUser($user['id'], $form->getData(), $encodedPassword);
                    return $app->redirect($app['url_generator']->generate('/register/success'), 301);
                } else {
                    $app['session']->getFlashBag()->add(
                        'message', array(
                        'type' => 'warning', 'content' => 'Login is already being used')
                    );
                    return $app['twig']->render('users/add.twig', array('form' => $form->createView()));
                }
            } else {
                $app['session']->getFlashBag()->add(
                    'message', array('type' => 'warning', 'content' => 'Passwords are not the same')
                );
                return $app['twig']->render('users/add.twig', array('form' => $form->createView()));
            }
        }

        return $app['twig']->render('users/add.twig', array('form' => $form->createView()));
    }
}