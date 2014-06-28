<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

use Model\ProductsModel;
use Model\CategoriesModel;
use Model\ProducentsModel;
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

        $categoriesModel = new CategoriesModel($app);
        $test = $categoriesModel->getCategories();

        $choiceCategory = array(0=>'Choose..');

        for ( $i=0; $test[$i] != NULL; $i++) {
            array_push($choiceCategory, $test[$i]['name']);
        }

        $producentModel = new ProducentsModel($app);
        $test = $producentModel->getProducents();

        $choiceProducent = array(0=>'Choose..');

        for ( $i=0; $test[$i] != NULL; $i++) {
            array_push($choiceProducent, $test[$i]['name']);
        }

        
        $form = $app['form.factory']->createBuilder('form', $data)
            ->add(
                'name', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))           
                )
            )
            ->add(
                'idCategory', 'choice', array(
                'choices' => $choiceCategory
                )
            )
            ->add(
                'idProducent', 'choice', array(
                'choices' => $choiceProducent
                )
            )
            ->add(
                'price_brutto', 'number', array(
                'constraints' => array(new Assert\NotBlank())
                )
            )
            ->add(
                'desc', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                )
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $data['name'] = $app->escape($data['name']);
            $data['idCategory'] = $app->escape($data['idCategory']);
            $data['idProducent'] = $app->escape($data['idProducent']);
            $data['price_brutto'] = $app->escape($data['price_brutto']);
            $data['desc'] = $app->escape($data['desc']);
            $productsModel = new ProductsModel($app);
            $productsModel->addProduct($form->getData($data));
            return $app->redirect($app['url_generator']->generate('/products/'), 301);
        }

        return $app['twig']->render('products/add.twig', array('form' => $form->createView()));
    }

    public function edit(Application $app, Request $request)
    {
        $productsModel = new ProductsModel($app);
        $id = (int) $request->get('id', 0);
        $product = $productsModel->getProduct($id);

        $data = array();

        $categoriesModel = new CategoriesModel($app);
        $test = $categoriesModel->getCategories();

        $choiceCategory = array(0=>'Choose..');

        for ( $i=0; $test[$i] != NULL; $i++) {
            array_push($choiceCategory, $test[$i]['name']);
        }

        $producentModel = new ProducentsModel($app);
        $test = $producentModel->getProducents();

        $choiceProducent = array(0=>'Choose..');

        for ( $i=0; $test[$i] != NULL; $i++) {
            array_push($choiceProducent, $test[$i]['name']);
        }

        if (count($product)) {

            $form = $app['form.factory']->createBuilder('form', $product)
            ->add(
                'idCategory', 'choice', array(
                'choices' => $choiceCategory
                )
            )
            ->add(
                'idProducent', 'choice', array(
                'choices' => $choiceProducent
                )
            )
            ->add(
                'name', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                )
            )
            ->add(
                'price_brutto', 'number', array(
                'constraints' => array(new Assert\NotBlank())
                )
            )
            ->add(
                'desc', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                )
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $data['name'] = $app->escape($data['name']);
            $data['idCategory'] = $app->escape($data['idCategory']);
            $data['idProducent'] = $app->escape($data['idProducent']);
            $data['price_brutto'] = $app->escape($data['price_brutto']);
            $data['desc'] = $app->escape($data['desc']);
            $productsModel = new ProductsModel($app);
            $productsModel->saveProduct($data);
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

