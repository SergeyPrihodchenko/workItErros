<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP;

use Sergo\PHP\Class\Exception\HttpException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryPosts;

class FindByUUIDinPosts implements InterfaceAction {

    public function __construct(
        private InterfaceRepositoryPosts $repository
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {

            $uuid = $request->query('uuid');
            
        } catch (HttpException $e) {
            
            return new ErrorResponse($e->getMessage());
        }

        try {

            $post = $this->repository->getByUUIDinPosts(new UUID($uuid));

        } catch (HttpException $e) {

            return new ErrorResponse($e->getMessage());

        }

        return new SuccessfulResponse([
            'author_uuid' => $post->uuid(),
            'text' => $post->text()
        ]);
    }
}