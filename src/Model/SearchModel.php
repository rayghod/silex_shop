<?php

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;

class SearchModel
{

	protected $_db;

    public function __construct(Application $app)
    {
        $this->_db = $app['db'];
    }

    public function getCategories()
    {
        $sql = 'SElECT * FROM categories;';
        return $this->_db->fetchAll($sql);
    }

    public function getProductsBy($id)
    {
        $sql = 'SElECT products.*, categories.name as Kategoria, producents.name as Producent FROM products JOIN categories ON products.idCategory = categories.id JOIN producents ON products.idProducent = producents.id WHERE products.idCategory = ?;';
        return $this->_db->fetchAll($sql, array((int) $id));
    }

    public function getProductsFor($word)
    {
        $sql = "SElECT products.*, categories.name as Kategoria, producents.name as Producent FROM products JOIN categories ON products.idCategory = categories.id JOIN producents ON products.idProducent = producents.id WHERE products.name LIKE LOWER(\"%".$word."%\")";
        return $this->_db->fetchAll($sql);
    }

}