<?php

namespace User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Model\UsersModel;

class UserProvider implements UserProviderInterface
{

    protected $_app;

    public function __construct($app)
    {
        $this->_app = $app;
    }

    public function loadUserByUsername($login)
    {
        $userModel = new UsersModel($this->_app);
        $user = $userModel->loadUserByLogin($login);
        return new User($user['login'], $user['password'], $user['roles'], true, true, true, true);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}