<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP\Create;

use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Exceptions\AuthException;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Class\Users\Comments;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Authentification\InterfaceAuthentification;
use Sergo\PHP\Interfaces\Authentification\InterfaceTokenAuthentification;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryComments;

class AddComments implements InterfaceAction {

    public function __construct(
        private InterfaceRepositoryComments $repository,
        private LoggerInterface $logger,
        private InterfaceTokenAuthentification $Authentification

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
            $author_uuid = trim($request->jsonBodyField('author_uuid'));
            $post_uuid = trim($request->jsonBodyField('post_uuid'));
            $text = trim($request->jsonBodyField('text'));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->repository->save(new Comments(UUID::random(), new UUID($author_uuid), new UUID($post_uuid), $text));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse(['text' => $text]);
    }
}