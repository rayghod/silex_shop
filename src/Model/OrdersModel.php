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

    public function getCart($login)
    {
    	$sql = "SELECT * FROM users WHERE login =\"".$login."\";";
		$user = $this->_db->fetchAssoc($sql);
    	$sql = "SELECT * FROM orders WHERE idUser =\"".$user['id']."\" AND closed = 0;";
    	$orderId= $this->_db->fetchAssoc($sql);
    	
    	$sql2 = "SELECT products.name as name, products.price_netto as `price_netto`, 
    	products.price_brutto as `price_brutto`, products.desc as `desc`,
    	products.id as id
    	FROM orders_products JOIN products 
    	ON orders_products.idProduct = products.id 
    	WHERE orders_products.idOrder =\"".$orderId['id']."\" ;";
    	return $this->_db->fetchAll($sql2);
    }

	public function getOrder($login)
	{
		$sql = "SELECT * FROM users WHERE login =\"".$login."\";";
		$user = $this->_db->fetchAssoc($sql);
		$sql = "SELECT * FROM orders WHERE idUser  =\"".$user['id']."\";";
		return $this->_db->fetchAssoc($sql);
    }

    public function addToCart($idProduct, $idOrder)
    {
    	$sql = "INSERT INTO orders_products (idOrder, idProduct) VALUES(?,?)";
    	return $this->_db->executeQuery($sql, array($idOrder, $idProduct));
    }

   	public function removeFromCart($idProduct, $idOrder)
   	{
   		$sql = 'DELETE FROM orders_products WHERE idProduct= ? AND idOrder = ?';
   		return $this->_db->executeQuery($sql, array($idProduct, $idOrder));
   	}
}