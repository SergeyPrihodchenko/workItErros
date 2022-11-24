<?php

namespace Sergo\PHP\Class\Authentification;

use Sergo\PHP\Class\Exceptions\AuthException;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\Exceptions\RepositoryException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Authentication\InterfaceIdentification;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

class JsonBodyUuidIdentification implements InterfaceIdentification {

    public function __construct(
        private InterfaceRepositoryUsers $repository
    )
    {
        
    }

    public function user(Request $request): User
    {
        try {

            $userUuid = new UUID($request->jsonBodyField('user_uuid'));

        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            return $this->repository->getByUUIDInUsers($userUuid);

        } catch (RepositoryException $e) {
            throw new AuthException($e->getMessage());
        }
    }
}