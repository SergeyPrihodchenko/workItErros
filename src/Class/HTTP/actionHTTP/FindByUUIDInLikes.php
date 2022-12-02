<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP;

use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryLikes;

class FindByUUIDInLikes implements InterfaceAction {

    public function __construct(
        private InterfaceRepositoryLikes $repository
    )
    {
        
    }

    public function handle(Request $request): Response
    {
        try {
            $uuid = trim($request->query('uuid'));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $liks = $this->repository->getByPostUuid($uuid);
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse(['likes' => $liks]);
    }
}