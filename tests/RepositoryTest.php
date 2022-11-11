<?php
namespace tests\action;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Sergo\PHP\Class\Exception\RepositoryException;
use Sergo\PHP\Class\Persone\Name;
use Sergo\PHP\Class\Repository\RepositoryComments;
use Sergo\PHP\Class\Repository\RepositoryPosts;
use Sergo\PHP\Class\Repository\RepositoryUsers;
use Sergo\PHP\Class\Users\Comments;
use Sergo\PHP\Class\Users\Posts;
use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Class\UUID\UUID;

class RepositoryTest extends TestCase
{
    public function testWorkInUsersRepository(): void
    {
        $connectionStab = $this->createStub(PDO::class);

        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
        ->expects($this->once())
        ->method('execute')
        ->with([
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':username' => 'nikitin ivan',
            ':first_name' => 'ivan',
            ':last_name' => 'nikitin',
        ]);

        $connectionStab->method('prepare')->willReturn($statementMock);

        $repository = new RepositoryUsers($connectionStab);

        $repository->save(new User(new UUID('123e4567-e89b-12d3-a456-426614174000'), new Name('ivan', 'nikitin')));
    }

    public function testWorkInPostsRepository(): void
    {
        $connectionStab = $this->createStub(PDO::class);

        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
        ->expects($this->once())
        ->method('execute')
        ->with([
            ':uuid' => new UUID('123e4567-e89b-12d3-a456-426614174000'),
            ':author_uuid' => '123e4567-e89b-12d3-a456-426614174012',
            'text' => 'text',
            'title' => 'title',

        ]);

        $connectionStab->method('prepare')->willReturn($statementMock);

        $repository = new RepositoryPosts($connectionStab);

        $repository->save(new Posts(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            new UUID('123e4567-e89b-12d3-a456-426614174012'),
            'title',
            'text'));
    }

    public function testWorkInCommentsRepository(): void 
    {
        $connectionStab = $this->createStub(PDO::class);

        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
        ->expects($this->once())
        ->method('execute')
        ->with([
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':post_uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':author_uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':text' => 'text'
        ]);

        $connectionStab->method('prepare')->willReturn($statementMock);

        $repository = new RepositoryComments($connectionStab);

        $repository->save(new Comments(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            'text'));
    }
}
