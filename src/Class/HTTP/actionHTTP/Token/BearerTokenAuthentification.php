<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP\Token;

use DateTimeImmutable;
use Sergo\PHP\Class\Exceptions\AuthException;
use Sergo\PHP\Class\Exceptions\AuthTokenNotFoundException;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Interfaces\Authentification\InterfaceTokenAuthentification;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryAuthToken;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

class BearerTokenAuthentification implements InterfaceTokenAuthentification
{

    private const HEADER_PREFIX = 'Bearer';

    public function __construct(
        private InterfaceRepositoryAuthToken $repoToken,
        private InterfaceRepositoryUsers $repoUsers
    ) {
    }

    public function user(Request $request): User
    {
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            throw new AuthException("Malformed token: [$header]");
        }

        $token = mb_substr($header, strlen(self::HEADER_PREFIX));

        try {
            $authToken = $this->repoToken->get($token);
        } catch (AuthTokenNotFoundException) {
            throw new AuthException("Bad token: [$token]");
        }

        if ($authToken->expiresOn() <= new DateTimeImmutable()) {
            throw new AuthException("Token expired: [$token]");
        }

        $userUuid = $authToken->userUuid();

        return $this->repoUsers->getByUUIDInUsers($userUuid);
    }
}
