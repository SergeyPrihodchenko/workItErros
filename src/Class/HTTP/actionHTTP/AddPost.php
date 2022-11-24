<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP;

use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Class\Users\Posts;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryPosts;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Authentication\InterfaceIdentification;

class AddPost implements InterfaceAction {

    public function __construct(
        private  InterfaceRepositoryPosts $repository,
        private InterfaceIdentification $identefication,
        private LoggerInterface $logger
    )
    {
        
    }

    public function handle(Request $request): Response
    {
        $author = $this->identefication->user($request);

        $newPostUuid = UUID::random();

        try {

            $post = new Posts(
                $newPostUuid,
                new UUID($author->uuid()),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text')
            );

        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            if ($this->repository->getByUuidAuthorInPosts($authorUuid) !== false) {
                $this->repository->save($post);
            }
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse(['uuid' => $post->uuid()]);
    }
}