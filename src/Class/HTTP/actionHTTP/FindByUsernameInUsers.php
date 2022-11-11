<?php

namespace Sergo\PHP\Class\HTTP\actionHTTP;

use Sergo\PHP\Class\Exception\HttpException;
use Sergo\PHP\Class\Exception\UserNotFoundException;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;
use Sergo\PHP\Class\HTTP\Response\Response;
use Sergo\PHP\Class\HTTP\Response\SuccessfulResponse;
use Sergo\PHP\Interfaces\HTTP\actionHTTP\InterfaceAction;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

class FindByUsernameInUsers implements InterfaceAction
{
    public function __construct(
        private InterfaceRepositoryUsers $repositoryUsers
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {

            $username = $request->query('username');

        } catch (HttpException $e) {

            return new ErrorResponse(($e->getMessage()));

        }

        try {

            $user = $this->repositoryUsers->getByUsernameInUsers($username);

        } catch (UserNotFoundException $e) {

            return new ErrorResponse($e->getMessage());

        }

        return new SuccessfulResponse([
            'username' => $user->full_name(),
            'name' => $user->first_name()
        ]);
    }
}