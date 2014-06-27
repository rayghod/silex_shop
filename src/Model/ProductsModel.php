<?php

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;

class ProductsModel
{

	protected $_db;

    public function __construct(Application $app)
    {
        $this->_db = $app['db'];
    }

    public function getProduct($id)
    {
        if (($id != '') && ctype_digit((string)$id)) {
            $sql = 'SELECT * FROM products WHERE id= ?';
            return $this->_db->fetchAssoc($sql, array((int) $id));
        } else {
            return array();
        }
    }

    public function getProducts()
    {
        $sql = 'SELECT products.*, categories.name as Kategoria, producents.name as Producent FROM products JOIN categories ON products.idCategory = categories.id JOIN producents ON products.idProducent = producents.id;';
        return $this->_db->fetchAll($sql);
    }

    public function addProduct($data)
    {
        $sql = 'INSERT INTO `products` ( `id`, `idCategory`, `idProducent`, `name`, `price_netto`, `price_brutto`, `desc` ) VALUES (NULL,?,?,?,?,?,?)';
        $this->_db->executeQuery($sql, array($data['idCategory'], $data['idProducent'], $data['name'], $data['price_netto'], $data['price_brutto'], $data['desc']));
    }

    public function saveProduct($data)
    {
        if (isset($data['id']) && ctype_digit((string)$data['id'])) {
            $sql = 'UPDATE products SET idCategory = ?, idProducent = ?, name = ?, price_netto = ?, price_brutto = ?, `desc` = ? WHERE id = ?';
            $this->_db->executeQuery($sql, array($data['idCategory'], $data['idProducent'], $data['name'], $data['price_netto'], $data['price_brutto'], $data['desc'], $data['id']));
        } else {
            $sql = 'INSERT INTO `products` ( `id`, `idCategory`, `idProducent`, `name`, `price_netto`, `price_brutto`, `desc` ) VALUES (NULL,?,?,?,?,?,?)';
            $this->_db->executeQuery($sql, array($data['id']), array($data['idCategory'], $data['idProducent'], $data['name'], $data['price_netto'], $data['price_brutto'], $data['desc']));        }
    }
    public function deleteProduct($id)
    {
        $sql = 'DELETE FROM products WHERE id= ?';
        return $this->_db->executeQuery($sql, array((int) $id));
    }

    

}