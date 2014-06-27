<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class IndexController implements ControllerProviderInterface
{

    public function connect(Application $app)
    {
        $indexController = $app['controllers_factory'];
        $indexController->get('/', array($this, 'index'));
        return $indexController;
    }

    public function index(Application $app)
    {
    	return $app['twig']->render('base.twig');
    }
}