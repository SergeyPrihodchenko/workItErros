<?php

namespace Sergo\PHP\Class\Commands\Posts;

use Sergo\PHP\Class\Exceptions\PostNotFoundException;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryPosts;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeletePost extends Command
{

    public function __construct(
        private InterfaceRepositoryPosts $repository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('posts:delete')
            ->setDescription('Delete a post')
            ->addArgument(
                'uuid',
                InputArgument::REQUIRED,
                'UUID of a post to delete'
            )
            ->addOption(
                'check-existence',
                'c',
                InputOption::VALUE_NONE,
                'Check if post actually exists'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $question = new ConfirmationQuestion(
            'Delete post [Y/N]? ',
            false
        );

        if (!$this->getHelper('question')
            ->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }

        $uuid = new UUID($input->getArgument('uuid'));

        if ($input->getOption('check-existence')) {
            try {
                $this->repository->getByUuidAuthorInPosts($uuid);
            } catch (PostNotFoundException $e) {
                $output->writeln($e->getMessage());
                return Command::FAILURE;
            }
        }

        $this->repository->delete($uuid);

        $output->writeln("Post $uuid deleted");

        return Command::SUCCESS;
    }
}
