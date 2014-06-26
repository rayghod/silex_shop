<?php

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;

class OrdersModel
{
	protected $_db;

    public function __construct(Application $app)
    {
        $this->_db = $app['db'];
    }

	public function getCart($login){
		$sql2 = "SELECT * FROM users WHERE login =\"".$login."\";";
		$user = $this->_db->fetchAssoc($sql2);
		$sql = "SELECT * FROM orders WHERE idUser  =\"".$user['id']."\";";
		return $this->_db->fetchAll($sql);
    }
}