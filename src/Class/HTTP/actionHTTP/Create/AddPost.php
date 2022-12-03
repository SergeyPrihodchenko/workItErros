<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP\Create;

use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Exceptions\AuthException;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Class\Users\Posts;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryPosts;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Authentification\InterfaceAuthentification;

class AddPost implements InterfaceAction {

    public function __construct(
        private  InterfaceRepositoryPosts $repository,
        private InterfaceAuthentification $Authentification,
        private LoggerInterface $logger
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

        $newPostUuid = UUID::random();

        try {

            $post = new Posts(
                new UUID($newPostUuid),
                new UUID($author->uuid()),
                trim($request->jsonBodyField('title')),
                trim($request->jsonBodyField('text'))
            );

        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->repository->save($post);

        return new SuccessfulResponse(['uuid' => $post->uuid()]);
    }
}