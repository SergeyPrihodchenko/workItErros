<?php

namespace Sergo\PHP\Class\Commands\Users;

use Sergo\PHP\Class\Persone\Name;
use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUser extends Command
{

    public function __construct(
        private InterfaceRepositoryUsers $repository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('users:update')
            ->setDescription('Updates a user')
            ->addArgument(
                'uuid',
                InputArgument::REQUIRED,
                'UUID of a user to update'
            )
            ->addOption(
                'first-name',
                'f',
                InputOption::VALUE_OPTIONAL,
                'First name',
            )
            ->addOption(
                'last-name',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Last name'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $firstName = $input->getOption('first-name');
        $lastName = $input->getOption('last-name');

        if (empty($firstName) && empty($lastName)) {
            $output->writeln('Nothing to update');
            return Command::FAILURE;
        }

        $uuid = new UUID($input->getArgument("uuid"));

        $user = $this->repository->getByUUIDInUsers($uuid);

        $updatedName = new Name(
            empty($firstName) ? $user->first_name() : $firstName,
            empty($lastName) ? $user->last_name() : $lastName
        );

        $updateUser = new User(
            $uuid,
            $updatedName,
            $user->hashedPassword()
        );

        $this->repository->save($updateUser);

        $output->writeln("User update: $uuid");

        return Command::SUCCESS;
    }
}
