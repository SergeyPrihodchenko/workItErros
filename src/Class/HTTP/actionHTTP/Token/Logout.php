<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP\Token;

use Exception;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\Exceptions\RepositoryException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryAuthToken;

class Logout implements InterfaceAction
{

    public function __construct(
        private InterfaceRepositoryAuthToken $repository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $header = $request->header("Authorization");
            $token = mb_substr($header, strlen("Bearer"));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->repository->update($token);
        } catch (RepositoryException $e) {
            throw new RepositoryException($e->getMessage());
        }

        return new SuccessfulResponse(["logout" => 'ok']);
    }
}
