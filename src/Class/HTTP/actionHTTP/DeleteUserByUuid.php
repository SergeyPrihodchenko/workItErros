<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP;

use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\Exceptions\RepositoryException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

class DeleteUserByUuid implements InterfaceAction {

    public function __construct(
        private InterfaceRepositoryUsers $repository
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $uuid = trim($request->jsonBodyField('user_uuid'));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->repository->delete($uuid);
        } catch (HttpException $e) {
            throw new RepositoryException($e->getMessage());
        }

        return new SuccessfulResponse(['message' => "User delted UUID: $uuid"]);
    }
}