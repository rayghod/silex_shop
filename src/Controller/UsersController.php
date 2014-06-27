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
        $usersController->match('/', array($this, 'index'))->bind('/register/');
        return $usersController;
    }

    public function index(Application $app, Request $request){

        $data = array();
        $form = $app['form.factory']->createBuilder('form', $data)
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
            $data = $form->getData();
            if ($data['password'] === $data['confirm_password']) {
              
                $encodedPassword = $app['security.encoder.digest']->encodePassword($data['password'], '');
                
                $usersModel = new UsersModel($app);
                $checkLogin = $usersModel->getUserByLogin($data['login']);
                if(!$checkLogin){
                    $usersModel->registerUser($form->getData(), $encodedPassword);
                    return $app['twig']->render('users/add.twig', array('form' => $form->createView()));
                }
                else{
                    $app['session']->getFlashBag()->add('message', array('type' => 'warning', 'content' => 'Login is already being used'));
                    return $app['twig']->render('users/add.twig', array('form' => $form->createView()));
                }
            }
            else{
                $app['session']->getFlashBag()->add('message', array('type' => 'warning', 'content' => 'Passwords are not the same'));
                return $app['twig']->render('users/add.twig', array('form' => $form->createView()));
            }
        }

        return $app['twig']->render('users/add.twig', array('form' => $form->createView()));
	}

}