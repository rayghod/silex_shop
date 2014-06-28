<?php
/**
 * Class CategoriesModel
 *
 * @class CategoriesModel
 * @package Model
 * @author Szymon Witkowski
 * @link wierzba.wzks.uj.edu.pl/projekt_php/
 * @uses Doctrine\DBAL\DBALException
 * @uses Silex\Application
 */
namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;

class OrdersModel
{
    /**
     * Database access object.
     *
     * @access protected
     * @var $_db Doctrine\DBAL
     */
    protected $_db;

    /**
     * Class constructor.
     *
     * @access public
     * @param Appliction $app Silex application object
     */
    public function __construct(Application $app)
    {
        $this->_db = $app['db'];
    }

    /**
     * Gets content of user's cart by his login.
     *
     * @access public
     * @param  $login Login of user that posses this Cart
     * @return array Cart assoc array
     * 
     */
    public function getCart($login)
    {
    	$sql = "SELECT * FROM users WHERE login =\"".$login."\";";
        $user = $this->_db->fetchAssoc($sql);
    	$sql = "SELECT * FROM orders WHERE idUser =\"".$user['id']."\" AND closed = 0;";
    	$orderId= $this->_db->fetchAssoc($sql);
    	
    	$sql2 = "SELECT 
                    products.name as name, products.price_netto as `price_netto`, 
                    products.price_brutto as `price_brutto`, products.desc as `desc`,
                    products.id as id
                FROM 
                    orders_products JOIN products 
    	        ON 
                    orders_products.idProduct = products.id 
    	        WHERE 
                    orders_products.idOrder =\"".$orderId['id']."\" ;";
    	return $this->_db->fetchAll($sql2);
    }

    /**
     * Gets order of user by his login.
     *
     * @access public
     * @param  @login Login of user that made this order
     * @return array Order assoc array
     */
    public function getOrder($login)
    {
        $sql = "SELECT * FROM users WHERE login =\"".$login."\";";
        $user = $this->_db->fetchAssoc($sql);
        $sql = "SELECT * FROM orders WHERE idUser  =\"".$user['id']."\" AND closed = 0;";
        return $this->_db->fetchAssoc($sql);
    }
    
    /**
     * Gets all closed Orders.
     *
     * @access public
     * @return array Order array
     */
    public function getFinishedOrders()
    {
        $sql = "SELECT * FROM orders WHERE closed = 1";
        return $this->_db->fetchAll($sql);
    }

    /**
     * Gets Products that are contented to this Order.
     *
     * @access public
     * @return array Products array
     * @param  $id id of Order
     */
    public function getProductsFromOrder($id)
    {
        $sql = "SELECT products.name as name, products.price_netto as `price_netto`, 
        products.price_brutto as `price_brutto`, products.desc as `desc`,
        products.id as id
        FROM orders_products JOIN products 
        ON orders_products.idProduct = products.id 
        WHERE orders_products.idOrder =\"".$id."\" ;";
        return  $this->_db->fetchAll($sql);
    }

    /**
     * Adds Product to Cart.
     * @access public
     * @param $idProduct id of Product
     * @param  $idOrder id of Order
     */
    public function addToCart($idProduct, $idOrder)
    {
    	$sql = "INSERT INTO orders_products (idOrder, idProduct) VALUES(?,?)";
    	return $this->_db->executeQuery($sql, array($idOrder, $idProduct));
    }

    /**
     * Deletes Product from Cart.
     * @access public
     * @param $idProduct id of Product
     * @param  $idOrder id of Order
     */
   	public function removeFromCart($idProduct, $idOrder)
   	{
   		$sql = 'DELETE FROM orders_products WHERE idProduct= ? AND idOrder = ?';
   		return $this->_db->executeQuery($sql, array($idProduct, $idOrder));
   	}

    /**
     * Closes Cart and makes it closed Order.
     * @access public
     * @param $order array of Order values
     * @param  $login Login of user
     */
    public function finishOrder($order, $login)
    {
        $sql = "SELECT * FROM users WHERE login =\"".$login."\";";
        $user = $this->_db->fetchAssoc($sql);
        $sql = "UPDATE orders SET closed = 1, street = ?, house_number = ?, postal_code = ?, city = ? WHERE idUser = ?";
        $this->_db->executeQuery(
            $sql, array(
            $order['street'], $order['house_number'], $order['postal_code'], $order['city'], $user['id'])
        );
        $sql = "INSERT INTO 
                    `orders` (`id`, `idUser`,`street`,`house_number`,`postal_code`,`city`, `closed`) 
                VALUES 
                    (NULL, ?, ?, ?, ?, ?, ?); ";
        $this->_db->executeQuery(
            $sql, array(
            $user['id'], $order['street'], $order['house_number'], $order['postal_code'], $order['city'], 0)
        );
    }
}