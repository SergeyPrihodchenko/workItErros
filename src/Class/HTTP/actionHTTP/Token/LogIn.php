<?php 

namespace Sergo\PHP\Class\HTTP\actionHTTP\Token;

use DateTimeImmutable;
use Sergo\PHP\Class\Authentification\AuthToken;
use Sergo\PHP\Class\Exceptions\AuthException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Authentification\InterfacePasswordAuthentification;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryAuthToken;

class LogIn implements InterfaceAction {

    public function __construct(
        private InterfacePasswordAuthentification $passwordAuthentification,
        private InterfaceRepositoryAuthToken $authTokensRepository
    )
    {   
    }

    public function handle(Request $request): Response
    {
        try {
            $user = $this->passwordAuthentification->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $authtoken = new AuthToken(
            bin2hex(random_bytes(40)),
            new UUID($user->uuid()),
            (new DateTimeImmutable())->modify('+1 day')
        );

        $this->authTokensRepository->save($authtoken);

        return new SuccessfulResponse([
            'token' => (string)$authtoken->token(),
        ]);
    }
}