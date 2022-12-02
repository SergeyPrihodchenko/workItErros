<?php

namespace Sergo\PHP\Class\Authentification;

use Sergo\PHP\Class\Exceptions\AuthException;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\Exceptions\RepositoryException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Interfaces\Authentification\InterfacePasswordAuthentification;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

class PasswordAuthentification implements InterfacePasswordAuthentification {

    public function __construct(
        private InterfaceRepositoryUsers $repository
    )
    {
    }

    public function user(Request $request): User
    {
        try {
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            $user = $this->repository->getByUsernameInUsers($username);
        } catch (RepositoryException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

  
        if(!$user->checkPassword($password)) {
            throw new AuthException("Wrong password");
        }
        
        return $user;
    }
}