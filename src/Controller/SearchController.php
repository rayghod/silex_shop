<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

use Model\ProductsModel;
use Model\CategoriesModel;

class SearchController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $searchController = $app['controllers_factory'];
        $searchController->match('/', array($this, 'index'))->bind('/search/');
        $searchController->match('/by/{id}', array($this, 'searchBy'))->bind('/search/by/');
        $searchController->match('/for/{word}', array($this, 'searchFor'))->bind('/search/for/');
        return $searchController;
    }

    public function index(Application $app, Request $request)
    {
    	
        $data = array();

        $form = $app['form.factory']->createBuilder('form', $data)
            ->add('phrase', 'text', array('constraints' => array(new Assert\NotBlank())))
            ->getForm();

        $form->handleRequest($request);    

        if ($form->isValid()) {
            $data = $form->getData();
            $data['phrase'] = $app->escape($data['phrase']);
            return $app->redirect($app['url_generator']->generate('/search/for/', array('word' => $data['phrase'])));
        }


        $data2 = array();

        $categoriesModel = new CategoriesModel($app);
        $test = $categoriesModel->getCategories();

        $choiceCategory = array(0=>'Choose..');

        for ( $i=0; $test[$i] != NULL; $i++) {
            array_push($choiceCategory, $test[$i]['name']);
        }

        $form2 = $app['form.factory']->createBuilder('form', $data2)
            ->add(
                'idCategory', 'choice', array(
                'choices' => $choiceCategory
                )
            )
            ->getForm();

        $form2->handleRequest($request);

        if ($form2->isValid()) {
            $data2 = $form2->getData();
            $data2['idCategory'] = $app->escape($data2['idCategory']);
            return $app->redirect(
                $app['url_generator']->generate(
                    '/search/by/', array('id' => $data2['idCategory'])
                ), 301
            );
        }
        
        return $app['twig']->render(
            'search.twig', array(
            'categories' => $categories, 'form' => $form->createView(), 'form2' => $form2->createView()
            )
        );
    }

    public function searchBy(Application $app, Request $request)
    {
    	$id = (int) $request->get('id', 0);
    	$productsModel = new ProductsModel($app);
    	$search= $productsModel->getProductsBy($id);
    	return $app['twig']->render('search-by.twig', array('products' => $search));
    }
    
    public function searchFor(Application $app, Request $request)
    {
        $word = $request->get('word');
        $productsModel = new ProductsModel($app);
        $search= $productsModel->getProductsFor($word);
        return $app['twig']->render('search-for.twig', array('products' => $search));
    }

}