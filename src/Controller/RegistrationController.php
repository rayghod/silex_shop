<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

use Model\UsersModel;

class RegistrationController implements ControllerProviderInterface
{
	public function connect(Application $app){
		$registerController = $app['controllers_factory'];
		$registerController->match('/', array($this, 'index'))->bind('/register/');
		return $registerController;
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
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
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
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $registrationModel = new UsersModel($app);
            $registrationModel->registerUser($form->getData());
            return $app->redirect($app['url_generator']->generate('/products/'), 301);
        }

        return $app['twig']->render('products/add.twig', array('form' => $form->createView()));
	}

}