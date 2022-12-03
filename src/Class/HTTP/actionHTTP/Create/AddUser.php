<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP\Create;

use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\Exceptions\RepositoryException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Class\Persone\Name;
use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

class AddUser implements InterfaceAction {

    public function __construct(
        private InterfaceRepositoryUsers $repository,
        private LoggerInterface $logger
    )
    {
        
    }

    public function handle(Request $request): Response
    {
        try {
            $userData = $request->jsonBodyField('user_data');
            
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = User::createFrom(
                new Name(
                    $userData['first_name'],
                    $userData['last_name']
                ),
                $userData['password']
            );
            
            $this->repository->save($user);
        } catch (\Throwable $e) {
            $this->logger->error("Fatal Error create user");
            throw new RepositoryException($e->getMessage());
        }

        return new SuccessfulResponse([
            'status' => 'ok',
            'UUID' => $user->uuid()]);
    }
}