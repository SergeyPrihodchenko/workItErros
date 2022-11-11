<?php

namespace Sergo\PHP\class\HTTP\actionHTTP;

use Sergo\PHP\Class\Exception\HttpException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryPosts;

class deletePost implements InterfaceAction {

    public function __construct(
        private InterfaceRepositoryPosts $repository
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            if($request->method() === 'DELETE') {
                $uuid = $request->query('uuid');
            }
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->repository->delete(new UUID($uuid));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse(['message' => 'Post deleted']);
    }
}