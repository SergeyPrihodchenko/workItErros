<?php

namespace Sergo\PHP\Class\Commands;

use Sergo\PHP\Class\Persone\Name;
use Sergo\PHP\Class\Users\Comments;
use Sergo\PHP\Class\Users\Posts;
use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryComments;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryPosts;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{
    public function __construct(
        private \Faker\Generator $faker,
        private InterfaceRepositoryUsers $repositoryUsers,
        private InterfaceRepositoryPosts $repositoryPosts,
        private InterfaceRepositoryComments $repositoryComments
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addOption(
                'count-users',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Count users'
            )
            ->addOption(
                'count-posts',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Count posts'
            )
            ->addOption(
                'count-comments',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Count comments'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = [];
        $posts = [];
        $count_users = $input->getOption('count-users');
        $count_posts = $input->getOption('count-posts');
        $count_comments = $input->getOption('count-comments');

        for ($i = 0; $i < $count_users; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created' . $user->full_name());
        }

        foreach ($users as $user) {
            for ($i = 0; $i < $count_posts; $i++) {
                $post = $this->createFakePost($user);
                $posts[] = $post;
                $output->writeln('Post created:' . $post->title());
            }
        }

        foreach ($posts as $post) {
            for ($i = 0; $i < $count_comments; $i++) {
                $comment = $this->createFakeComment($post);
                $output->writeln('Commet created' . $comment->text());
            }
        }

        return Command::SUCCESS;
    }

    private function createFakeUser(): User
    {
        $user = User::createFrom(
            new Name(
                $this->faker->firstName(),
                $this->faker->lastName(),
            ),
            $this->faker->password()
        );

        $this->repositoryUsers->save($user);

        return $user;
    }

    private function createFakePost(User $author): Posts
    {
        $post = new Posts(
            UUID::random(),
            new UUID($author->uuid()),
            $this->faker->sentence(6, true),
            $this->faker->realText()
        );

        $this->repositoryPosts->save($post);

        return $post;
    }

    private function createFakeComment(Posts $post): Comments
    {
        $comment = new Comments(
            UUID::random(),
            new UUID($post->idUser()),
            new UUID($post->uuid()),
            $this->faker->realText()
        );

        $this->repositoryComments->save($comment);

        return $comment;
    }
}
