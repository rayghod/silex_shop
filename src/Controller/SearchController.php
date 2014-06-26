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
        $searchController->get('/', array($this, 'index'))->bind('/search/');
        $searchController->match('/by/{id}', array($this, 'search'))->bind('/search/by/');
        return $searchController;
    }

	public function index(Application $app)
    {
    	$searchModel = new SearchModel($app);
    	$categories = $searchModel->getCategories();
        return $app['twig']->render('search.twig', array('categories' => $categories));
    }
    public function search(Application $app, Request $request){
    	$id = (int) $request->get('id', 0);
    	$searchModel = new SearchModel($app);
    	$search= $searchModel->getProducts($id);
    	 return $app['twig']->render('search-by.twig', array('products' => $search));
    }


}