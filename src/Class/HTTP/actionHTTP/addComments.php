<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP;

use Sergo\PHP\Class\Exception\HttpException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Class\Users\Comments;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryComments;

class AddComments implements InterfaceAction {

    public function __construct(
        private InterfaceRepositoryComments $repository
    )
    {
    }

    public function handle(Request $request): Response
    {

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