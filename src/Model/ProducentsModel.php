<?php

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;

class ProducentsModel
{
	protected $_db;

    public function __construct(Application $app)
    {
        $this->_db = $app['db'];
    }

    public function getProducent($id)
    {
        $sql = 'SELECT * FROM producents WHERE id =?;';
        return $this->_db->fetchAssoc($sql, array($id));
    }

    public function getProducents()
    {
        $sql = 'SELECT * FROM producents;';
        return $this->_db->fetchAll($sql);
    }
	public function addProducent($data)
    {
        $sql = 'INSERT INTO `producents` ( `id` ,`name`) VALUES (NULL, ?)';
        $this->_db->executeQuery($sql, array($data['name']));
    }

    public function editProducent($data, $id)
    {
        $sql = 'UPDATE producents SET name = ? WHERE id= ?';
        $this->_db->executeQuery($sql, array($data['name'], $id));
    }

    public function deleteProducent($id)
    {
        $sql = 'DELETE FROM producents WHERE id= ?';
        return $this->_db->executeQuery($sql, array((int) $id));
    }
}