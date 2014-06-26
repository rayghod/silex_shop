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
		$sql = "SELECT * FROM orders WHERE idUser  =\"".$user['id']."\" AND closed = 0;";
		return $this->_db->fetchAssoc($sql);
    }

    public function getFinishedOrders()
    {
        $sql = "SELECT * FROM orders WHERE closed = 1";
        return $this->_db->fetchAll($sql);
    }

    public function getProductsFromOrder($id)
    {
        $sql = "SELECT * FROM orders_products WHERE idOrder = ?";
        return  $this->_db->fetchAll($sql, array($id));
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

    public function finishOrder($order, $login)
    {
        $sql = "SELECT * FROM users WHERE login =\"".$login."\";";
        $user = $this->_db->fetchAssoc($sql);
        $sql = "UPDATE orders SET closed = 1, street = ?, house_number = ?, postal_code = ?, city = ? WHERE idUser = ?";
        $this->_db->executeQuery($sql, array($order['street'], $order['house_number'], $order['postal_code'], $order['city'], $user['id']));
        $sql = "INSERT INTO `orders` (`id`, `idUser`,`street`,`house_number`,`postal_code`,`city`, `closed`) VALUES (NULL, ?, ?, ?, ?, ?, ?); ";
        $this->_db->executeQuery($sql, array($user['id'], $order['street'], $order['house_number'], $order['postal_code'], $order['city'], 0));
    }
}