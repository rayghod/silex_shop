<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

use Model\CategoriesModel;

class CategoriesController implements ControllerProviderInterface
{

    public function connect(Application $app)
    {
        $categoriesController = $app['controllers_factory'];
        $categoriesController->get('/', array($this, 'index'))->bind('/categories/');
        $categoriesController->match('/add', array($this, 'add'))->bind('/categories/add');
        $categoriesController->match('/edit/{id}', array($this, 'edit'))->bind('/categories/edit');
    	$categoriesController->match('/delete/{id}', array($this, 'delete'))->bind('/categories/delete');
        return $categoriesController;
    }

    public function index(Application $app)
    {
        $categoriesModel = new CategoriesModel($app);
        $categories = $categoriesModel->getCategories();
        return $app['twig']->render('categories/categories.twig', array('products' => $categories));
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
            $categoriesModel = new CategoriesModel($app);
            $categoriesModel->addCategory($form->getData());
            return $app->redirect($app['url_generator']->generate('/categories/'), 301);
        }

        return $app['twig']->render('categories/add.twig', array('form' => $form->createView()));
    }

    public function edit(Application $app, Request $request)
    {
        $categoriesModel = new CategoriesModel($app);
        $id = (int) $request->get('id', 0);
        $category = $categoriesModel->getCategory($id);

        $form = $app['form.factory']->createBuilder('form', $category)
            ->add('name', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
            ))
            ->getForm();
    
        $form->handleRequest($request);

        if ($form->isValid()) {
            $categoriesModel = new CategoriesModel($app);
            $categoriesModel->editCategory($form->getData(), $id);
            return $app->redirect($app['url_generator']->generate('/categories/'), 301);
        }

        return $app['twig']->render('categories/edit.twig', array('form' => $form->createView(), 'category' => $category));
    }

    public function delete(Application $app, Request $request)
    {
        $id = (int) $request->get('id', 0);
        $categoriesModel = new CategoriesModel($app);
        $categoriesModel->deleteCategory($id);
        return $app->redirect($app['url_generator']->generate("/categories/"), 301);     
    }

}