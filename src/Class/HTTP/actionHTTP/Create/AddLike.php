<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP\Create;

use Sergo\PHP\Class\Exceptions\AuthException;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryLikes;
use Sergo\PHP\Class\Users\Like;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;

class AddLike implements InterfaceAction {

    public function __construct(
        private InterfaceRepositoryLikes $repositoryLikes
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
            $postuuid = trim($request->query('postuuid'));
            $useruuid = trim($request->query('useruuid'));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $like = new Like(
                UUID::random(),
                new UUID($postuuid),
                new UUID($useruuid)
            );

            $this->repositoryLikes->save($like);
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'uuid' => $like->UUID()
        ]);
    }
}