<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP;

use Sergo\PHP\Class\Exceptions\AuthException;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\Exceptions\RepositoryException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryComments;

class DeleteCommentByUuid implements InterfaceAction {

    public function __construct(
        private InterfaceRepositoryComments $repository
    )
    {
    }

    public function handle(Request $request): Response
    {

        try {
            $author = $this->Authentification->user($request);
        } catch (AuthException $e) {
            $this->logger->error('Not found user');
            return new ErrorResponse($e->getMessage());
        }

        try {
            $uuid = trim($request->jsonBodyField('comment_uuid'));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->repository->delete($uuid);
        } catch (HttpException $e) {
            throw new RepositoryException($e->getMessage());
        }

        return new SuccessfulResponse(['message' => "delete commest by UUID: $uuid"]);
    }
}