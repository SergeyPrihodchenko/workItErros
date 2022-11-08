<?php

namespace Sergo\PHP\class\HTTP\actionHTTP;

use Sergo\PHP\Class\Exception\HttpException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Class\Repository\RepositoryPosts;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;

class deletePost implements InterfaceAction {

    public function __construct(
        private RepositoryPosts $repository
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
            $this->repository->delete($uuid);
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse(['message' => 'Post deleted']);
    }
}