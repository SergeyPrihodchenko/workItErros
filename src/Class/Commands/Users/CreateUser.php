<?php

namespace Sergo\PHP\Class\Commands\Users;

use Sergo\PHP\Class\Exceptions\UserNotFoundException;
use Sergo\PHP\Class\Persone\Name;
use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    public function __construct(
        private InterfaceRepositoryUsers $repository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName("users:create")
            ->setDescription("Creates new user")
            ->addArgument('first_name', InputArgument::REQUIRED, "First name")
            ->addArgument("last_name", InputArgument::REQUIRED, "Last name")
            ->addArgument('password', InputArgument::REQUIRED, "Password")
            ->addArgument('username', InputArgument::REQUIRED, "Username");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create user command started');

        $username = $input->getArgument('username');
        if ($this->userExists($username)) {
            $output->writeln("User already exists: $username");

            return Command::FAILURE;
        }

        $user = User::createFrom(
            new Name(
                $input->getArgument("first_name"),
                $input->getArgument("last_name"),
            ),
            $input->getArgument('password')
        );

        $this->repository->save($user);
        var_dump($user->uuid());
        $output->writeln("User created:"  . $user->uuid());

        return Command::SUCCESS;
    }

    private function userExists(string $username): bool
    {
        try {
            $this->repository->getByUsernameInUsers($username);
        } catch (UserNotFoundException) {
            return false;
        }

        return true;
    }
}
