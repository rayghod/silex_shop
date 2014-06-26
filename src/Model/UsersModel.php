<?php

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UsersModel
{

    protected $_app;
    protected $_db;

    public function __construct(Application $app)
    {
        $this->_app = $app;
        $this->_db = $app['db'];
    }

    public function loadUserByLogin($login)
    {
        $data = $this->getUserByLogin($login);

        if (!$data) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $login));
        }

        $roles = $this->getUserRoles($data['id']);

        if (!$roles) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $login));
        }

        $user = array(
            'login' => $data['login'],
            'password' => $data['password'],
            'roles' => $roles
        );

        return $user;
    }

    public function getUserByLogin($login)
    {
        $sql = 'SELECT * FROM users WHERE login = ?';
        return $this->_db->fetchAssoc($sql, array((string) $login));
    }

    public function getUserRoles($userId)
    {
        $sql = '
            SELECT
                roles.role
            FROM
                users_roles
            INNER JOIN
                roles
            ON users_roles.role_id=roles.id
            WHERE
                users_roles.user_id = ?
            ';

        $result = $this->_db->fetchAll($sql, array((string) $userId));

        $roles = array();
        foreach($result as $row) {
            $roles[] = $row['role'];
        }

        return $roles;
    }

    public function registerUser($data)
    {
        $sql = 'INSERT INTO users (`id`, `login`, `password`, `firstname`, `lastname`, `email`, `phone_number`, `street`, `house_number`, `postal_code`, `city` ) VALUES( NULL, ? , ? , ? , ? , ? , ? , ? , ? , ?, ?); ';
        $this->_db->executeQuery($sql, array($data['login'], $data['password'], $data['firstname'], $data['lastname'], $data['email'], $data['phone_number'], $data['street'], $data['house_number'], $data['postal_code'], $data['city']));
        $sql2 = "SELECT * FROM users WHERE login =\"".$data['login']."\";";
        $user = $this->_db->fetchAssoc($sql2);
        $sql3 = 'INSERT INTO users_roles (`id`,`user_id`, `role_id` ) VALUES(NULL, ?, ?)';
        $this->_db->executeQuery($sql3, array($user['id'], 2));
    }
}