<?php

namespace App\Tests\Infrastructure\Doctrine;

use App\Domain\Queue\Queue;
use App\Domain\Track\Track;
use App\Infrastructure\Doctrine\DoctrineQueueStore;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineQueueStoreTest extends KernelTestCase
{
    private DoctrineQueueStore $queueStore;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->queueStore = $this->getFromContainer(DoctrineQueueStore::class);
        $this->queueStore->clear();
    }

    /**
     * @template T of Object
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    protected function getFromContainer(string $class)
    {
        $container = static::getContainer();

        /** @var T */
        $res = $container->get($class);

        return $res;
    }

    public function testGetCurrentQueue(): void
    {
        $this->assertInstanceOf(Queue::class, $this->queueStore->get());
    }

    public function testSaveAndRestoreQueue(): void
    {
        $queueStore = $this->queueStore;

        $queue = new Queue([]);
        $queue->addTrack(new Track(1, 'Artist A', 'Song 1'));
        $queueStore->save($queue);

        $queue = $queueStore->get();

        $tracks = $queue->getTracks();
        $this->assertCount(1, $tracks);
        $this->assertEquals('Artist A', $tracks[0]->getArtist());
    }

    public function testClearQueue(): void
    {
        $queueStore = $this->queueStore;

        $queue = new Queue([]);
        $queue->addTrack(new Track(1, 'Artist A', 'Song 1'));
        $queueStore->save($queue);

        $queueStore->clear();

        $queue = $queueStore->get();
        $this->assertCount(0, $queue->getTracks());
    }
}
