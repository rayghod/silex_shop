<?php

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;

class CategoriesModel
{
	protected $_db;

    public function __construct(Application $app)
    {
        $this->_db = $app['db'];
    }

	public function getCategories()
    {
        $sql = 'SELECT * FROM categories;';
        return $this->_db->fetchAll($sql);
    }

    public function addCategory($data)
    {
        $sql = 'INSERT INTO `categories` ( `id` ,`name`) VALUES (NULL, ?)';
        $this->_db->executeQuery($sql, array($data['name']));
    }

    public function deleteCategory($id)
    {
        $sql = 'DELETE FROM categories WHERE id= ?';
        return $this->_db->executeQuery($sql, array((int) $id));
    }
}