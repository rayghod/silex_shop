<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

use Model\SearchModel;

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
            return $app->redirect($app['url_generator']->generate('/search/for/', array('word' => $data['phrase'])));
        }

        $searchModel = new SearchModel($app);
        $categories = $searchModel->getCategories();
        
        return $app['twig']->render('search.twig', array('categories' => $categories, 'form' => $form->createView()));
    }

    public function searchBy(Application $app, Request $request){
    	$id = (int) $request->get('id', 0);
    	$searchModel = new SearchModel($app);
    	$search= $searchModel->getProductsBy($id);
    	 return $app['twig']->render('search-by.twig', array('products' => $search));
    }
    
    public function searchFor(Application $app, Request $request){
        $word = $request->get('word');
        $searchModel = new SearchModel($app);
        $search= $searchModel->getProductsFor($word);
        return $app['twig']->render('search-for.twig', array('products' => $search));
    }

}