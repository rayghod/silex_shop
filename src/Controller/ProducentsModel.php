<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

use Model\ProducentsModel;

class ProducentsController implements ControllerProviderInterface
{
	public function connect(Application $app)
    {
        $producentssController = $app['controllers_factory'];
        $producentssController->get('/', array($this, 'index'))->bind('/producents/');
        $producentssController->match('/add', array($this, 'add'))->bind('/producents/add');
    	$producentssController->match('/delete/{id}', array($this, 'delete'))->bind('/producents/delete');
        return $producentssController;
    }

    public function index(Application $app)
    {
        $producentsModel = new ProducentsModel($app);
        $producents = $producentsModel->getproducents();
        return $app['twig']->render('producents/producents.twig', array('products' => $producents));
    }

    public function add(Application $app, Request $request)
   	{
   		$data = array();
   		$form = $app['form.factory']->createBuilder('form', $data)
            ->add('name', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
            ))
            ->getForm();
   	
        $form->handleRequest($request);

        if ($form->isValid()) {
            $producentsModel = new producentsModel($app);
            $producentsModel->addCategory($form->getData());
            return $app->redirect($app['url_generator']->generate('/producents/'), 301);
        }

        return $app['twig']->render('producents/add.twig', array('form' => $form->createView()));
    }

    public function delete(Application $app, Request $request)
    {
        $id = (int) $request->get('id', 0);
        $producentsModel = new producentsModel($app);
        $category = $producentsModel->deleteCategory($id);
        return $app->redirect($app['url_generator']->generate("/producents/"), 301);     
    }

}