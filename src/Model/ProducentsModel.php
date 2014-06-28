<?php
/**
 * Class ProducentsModel
 *
 * @class ProducentsModel
 * @package Model
 * @author Szymon Witkowski
 * @link wierzba.wzks.uj.edu.pl/~12_witkowski/projekt_php/
 * @uses Doctrine\DBAL\DBALException
 * @uses Silex\Application
 */
namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;

class ProducentsModel
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
     * Gets Producent by id.
     *
     * @access public
     * @param  $id id of Producent
     * @return array Producent assoc array
     */
    public function getProducent($id)
    {
        $sql = 'SELECT * FROM producents WHERE id =?;';
        return $this->_db->fetchAssoc($sql, array($id));
    }

    /**
     * Gets all Producents.
     *
     * @access public
     * @return array Producents array
     */
    public function getProducents()
    {
        $sql = 'SELECT * FROM producents;';
        return $this->_db->fetchAll($sql);
    }

    /**
     * Adds Producent to database.
     * @access public
     * @param $data array of Producent values
     */
    public function addProducent($data)
    {
        $sql = 'INSERT INTO `producents` ( `id` ,`name`) VALUES (NULL, ?)';
        $this->_db->executeQuery($sql, array($data['name']));
    }

    /**
     * Updates Producent in database.
     * @access public
     * @param $data array of Producent values
     * @param $id id of Producent
     */
    public function editProducent($data, $id)
    {
        $sql = 'UPDATE producents SET name = ? WHERE id= ?';
        $this->_db->executeQuery($sql, array($data['name'], $id));
    }

    /**
     * Delete Producent from database.
     * @access public
     * @param $data array of Producent values
     * @param $id id of Producent
     */
    public function deleteProducent($id)
    {
        $sql = 'DELETE FROM producents WHERE id= ?';
        return $this->_db->executeQuery($sql, array((int) $id));
    }
}