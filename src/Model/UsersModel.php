<?php
/**
 * Class CategoriesModel
 *
 * @class CategoriesModel
 * @package Model
 * @author Szymon Witkowski
 * @link wierzba.wzks.uj.edu.pl/projekt_php/
 * @uses Doctrine\DBAL\DBALException
 * @uses Symfony\Component\Security\Core\Exception\UnsupportedUserException
 * @uses Symfony\Component\Security\Core\Exception\UsernameNotFoundException
 * @uses Silex\Application
 */

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UsersModel
{
    /**
     * Application access object.
     *
     * @access protected
     * @var $_app Silex application object
     */
    protected $_app;

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
     * Loads user by login.
     *
     * @access public
     * @param $login login of user
     * @return Array information about searching user.
     */
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

    /**
     * Gets user by login.
     *
     * @access public
     * @param  $login Login us user
     * @return array role of user
     */
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
        foreach ($result as $row) {
            $roles[] = $row['role'];
        }

        return $roles;
    }

    /**
     * Register user by login.
     *
     * @access public
     * @param $data array of User values
     * @param  $encodedPassword encoded password for user
     */
    public function registerUser($data, $encodedPassword)
    {
        $sql = 'INSERT INTO 
                    users (`id`, `login`, `password`, `firstname`, `lastname`, `email`, 
                            `phone_number`, `street`, `house_number`, `postal_code`, `city` ) 
                VALUES
                    ( NULL, ? , ? , ? , ? , ? , ? , ? , ? , ?, ?); ';
        $this->_db->executeQuery(
            $sql, array(
            $data['login'], $encodedPassword, $data['firstname'], $data['lastname'], 
            $data['email'], $data['phone_number'], $data['street'], $data['house_number'], 
            $data['postal_code'], $data['city'])
        );
        $sql2 = "SELECT * FROM users WHERE login =\"".$data['login']."\";";
        $user = $this->_db->fetchAssoc($sql2);
        $sql3 = 'INSERT INTO users_roles (`id`,`user_id`, `role_id` ) VALUES(NULL, ?, ?)';
        $this->_db->executeQuery($sql3, array($user['id'], 2));

        $sql = "INSERT INTO 
                    orders (`id`, `idUser`,`street`,`house_number`,`postal_code`,`city`, `closed`) 
                VALUES 
                    (NULL, ?, ?, ?, ?, ?, ?);";
        $this->_db->executeQuery(
            $sql, array(
            $user['id'], $data['street'], $data['house_number'], $data['postal_code'], $data['city'], 0)
        );

    }

     /**
     * Update user by login.
     *
     * @access public
     * @param array array of User values
     * @param  [varname] [description]
     */
    public function updateUser($id, $data, $encodedPassword)
    {
        $sql = "UPDATE 
                    users 
                SET 
                    login = ?, password = ?, firstname= ?, lastname = ?,
                    email = ?, phone_number = ?, street = ?, house_number = ?, 
                    postal_code = ?, city = ? 
                WHERE 
                    id = ?";
        $this->_db->executeQuery(
            $sql, array(
            $data['login'], $encodedPassword, $data['firstname'], 
            $data['lastname'], $data['email'], $data['phone_number'], 
            $data['street'], $data['house_number'], $data['postal_code'], 
            $data['city'], $id)
        );
    }
}