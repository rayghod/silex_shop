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

class ProductsModel
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
     * Gets Product by id.
     *
     * @access public
     * @param  $id id of Product
     * @return array Product assoc array
     */
    public function getProduct($id)
    {
            $sql = 'SELECT * FROM products WHERE id= ?';
            return $this->_db->fetchAssoc($sql, array((int) $id));
    }

    /**
     * Gets all Products.
     *
     * @access public
     * @return array Products array
     */
    public function getProducts()
    {
        $sql = 'SELECT 
                    products.*, 
                    categories.name as Kategoria, 
                    producents.name as Producent 
                FROM 
                    products 
                JOIN 
                    categories 
                ON 
                    products.idCategory = categories.id 
                JOIN 
                    producents 
                ON 
                    products.idProducent = producents.id;';
        return $this->_db->fetchAll($sql);
    }

    /**
     * Adds Product to database.
     * @access public
     * @param $data array of Product values
     */
    public function addProduct($data)
    {
        $sql = 'INSERT INTO 
                    `products` ( `id`, `idCategory`, `idProducent`, `name`, `price_netto`, `price_brutto`, `desc` ) 
                VALUES 
                    (NULL,?,?,?,?,?,?)';
        $data['price_netto'] = $data['price_brutto'] * 1.22;
        $this->_db->executeQuery(
            $sql, array(
            $data['idCategory'], $data['idProducent'], $data['name'], 
            $data['price_netto'], $data['price_brutto'], $data['desc'])
        );
    }

    /**
     * Updates Product in database.
     * @access public
     * @param $data array of Product values
     * @param $data array of Product values
     */
    public function saveProduct($data)
    {
        if (isset($data['id']) && ctype_digit((string)$data['id'])) {
            $sql = 'UPDATE 
                        products 
                    SET 
                        idCategory = ?, idProducent = ?, name = ?, 
                        price_netto = ?, price_brutto = ?, `desc` = ? 
                    WHERE 
                        id = ?';
            $data['price_netto'] = $data['price_brutto'] * 1.22;
            $this->_db->executeQuery(
                $sql, array(
                $data['idCategory'], $data['idProducent'], $data['name'], 
                $data['price_netto'], $data['price_brutto'], $data['desc'], $data['id'])
            );
        } else {
            $sql = 'INSERT INTO 
                        `products` ( `id`, `idCategory`, `idProducent`, `name`, `price_netto`, `price_brutto`, `desc` ) 
                    VALUES 
                        (NULL,?,?,?,?,?,?)';
            $data['price_netto'] = $data['price_brutto'] * 1.22;
            $this->_db->executeQuery(
                $sql, array($data['id']), array(
                $data['idCategory'], $data['idProducent'], $data['name'], 
                $data['price_netto'], $data['price_brutto'], $data['desc'])
            );        
        }
    }

    /**
     * Deletes Product from database.
     * @access public
     * @param $id id of Product
     */
    public function deleteProduct($id)
    {
        $sql = 'DELETE FROM products WHERE id= ?';
        return $this->_db->executeQuery($sql, array((int) $id));
    }
    /**
     * Gets Products by Category.
     *
     * @access public
     * @param  $id id of Category
     * @return array Products array
     */
    public function getProductsBy($id)
    {
        $sql = 'SELECT 
                    products.*, 
                    categories.name as Kategoria, 
                    producents.name as Producent 
                FROM 
                    products JOIN categories 
                ON 
                    products.idCategory = categories.id 
                JOIN 
                    producents 
                ON  
                    products.idProducent = producents.id 
                WHERE 
                    products.idCategory = ?;';
        return $this->_db->fetchAll($sql, array((int) $id));
    }

    /**
     * Gets Products by phrase.
     *
     * @access public
     * @param  $word phrase
     * @return array Products array
     */
    public function getProductsFor($word)
    {
        $sql = "SELECT 
                    products.*, 
                    categories.name as Kategoria, 
                    producents.name as Producent 
                FROM 
                    products JOIN categories 
                ON 
                    products.idCategory = categories.id 
                JOIN 
                    producents 
                ON 
                    products.idProducent = producents.id 
                WHERE 
                    products.name 
                LIKE 
                    LOWER(\"%".$word."%\")";
        return $this->_db->fetchAll($sql);
    }

    

}