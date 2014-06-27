<?php

namespace Controller;

use Model\ProductsModel;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ProductsController implements ControllerProviderInterface
{

    public function connect(Application $app)
    {
        $productsController = $app['controllers_factory'];
        $productsController->get('/', array($this, 'index'))->bind('/products/');
        $productsController->match('/add', array($this, 'add'))->bind('/products/add');
        $productsController->match('/edit/{id}', array($this, 'edit'))->bind('/products/edit');
    	$productsController->match('/delete/{id}', array($this, 'delete'))->bind('/products/delete');
        return $productsController;
    }

    public function index(Application $app)
    {
    	$productsModel = new ProductsModel($app);
    	$products = $productsModel->getProducts();
        return $app['twig']->render('products/products.twig', array('products' => $products));
    }

    public function add(Application $app, Request $request)
   	{
       
        $data = array();

        $form = $app['form.factory']->createBuilder('form', $data)
            ->add('idCategory', 'number', array('constraints' => array(new Assert\NotBlank())))
            ->add('idProducent', 'number', array('constraints' => array(new Assert\NotBlank())))
            ->add('name', 'text',array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))           
            ))
            ->add('price_netto', 'number', array('constraints' => array(new Assert\NotBlank())))
            ->add('price_brutto', 'number', array(
                'constraints' => array(new Assert\NotBlank())
            ))
            ->add('desc', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $productsModel = new ProductsModel($app);
            $productsModel->addProduct($form->getData());
            return $app->redirect($app['url_generator']->generate('/products/'), 301);
        }

        return $app['twig']->render('products/add.twig', array('form' => $form->createView()));
    }

    public function edit(Application $app, Request $request)
    {
        $productsModel = new ProductsModel($app);
        $id = (int) $request->get('id', 0);
        $product = $productsModel->getProduct($id);

        if (count($product)) {

            $form = $app['form.factory']->createBuilder('form', $product)
            ->add('idCategory', 'number', array(
                'constraints' => array(new Assert\NotBlank())
            ))
            ->add('idProducent', 'number', array(
                'constraints' => array(new Assert\NotBlank())
            ))
            ->add('name', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
            ))
            ->add('price_netto', 'number', array(
                'constraints' => array(new Assert\NotBlank())
            ))
            ->add('price_brutto', 'number', array(
                'constraints' => array(new Assert\NotBlank())
            ))
            ->add('desc', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
            ))
            ->add('save', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $productsModel = new ProductsModel($app);
            $productsModel->saveProduct($form->getData());
            return $app->redirect($app['url_generator']->generate('/products/'), 301);
        }

        return $app['twig']->render('products/edit.twig', array('form' => $form->createView(), 'product' => $product));

        } else {
            return $app->redirect($app['url_generator']->generate('/products/'), 301);
        }

    }

    public function delete(Application $app, Request $request)
    {
        $id = (int) $request->get('id', 0);
        $productsModel = new ProductsModel($app);
        $product = $productsModel->deleteProduct($id);
        return $app->redirect($app['url_generator']->generate("/products/"), 301);     
    }
}

