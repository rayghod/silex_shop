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

class CategoriesModel
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
     * Gets Category by id.
     *
     * @access public
     * @param  $id id of Category
     * @return array Category assoc array
     */
    public function getCategory($id)
    {
        $sql = 'SELECT * FROM categories WHERE id =?;';
        return $this->_db->fetchAssoc($sql, array($id));
    }

    /**
     * Gets all Categories.
     *
     * @access public
     * @return array Categories array
     */
     public function getCategories()
    {
        $sql = 'SELECT * FROM categories;';
        return $this->_db->fetchAll($sql);
    }
    /**
     * Adds Category to database.
     * @access public
     * @param $data array of Category values
     */
    public function addCategory($data)
    {
        $sql = 'INSERT INTO `categories` ( `id` ,`name`) VALUES (NULL, ?)';
        $this->_db->executeQuery($sql, array($data['name']));
    }

    /**
     * Updates Category in database.
     * @access public
     * @param $data array of Category values
     * @param $id id of Category
     */
    public function editCategory($data, $id)
    {
        $sql = 'UPDATE categories SET name = ? WHERE id= ?';
        $this->_db->executeQuery($sql, array($data['name'], $id));
    }
    /**
     * Deletes Category from database.
     * @access public
     * @param $id id of Category
     */
    public function deleteCategory($id)
    {
        $sql = 'DELETE FROM categories WHERE id= ?';
        $this->_db->executeQuery($sql, array((int) $id));
    }
}