<?php

namespace App\Infrastructure\Doctrine;

use App\Domain\Queue\Queue;
use App\Domain\Queue\QueueStore;
use App\Domain\Track\TrackRepository;
use Doctrine\DBAL\Connection;

readonly class DoctrineQueueStore implements QueueStore
{
    public function __construct(
        private Connection $connection,
        private TrackRepository $trackRepository
    ) {
    }

    public function get(): Queue
    {
        $queueData = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('queue')
            ->orderBy('position', 'ASC')
            ->fetchAllAssociative();

        $tracks = array_map(function ($trackData) {
            return $this->trackRepository->get($trackData['track_id']);
        }, $queueData);

        return new Queue($tracks);
    }

    public function save(Queue $queue): void
    {
        $this->clear();

        foreach ($queue->getTracks() as $position => $track) {
            $this->connection->createQueryBuilder()
                ->insert('queue')
                ->values([
                    'position' => $position,
                    'track_id' => $track->getId(),
                ])
                ->executeQuery();
        }
    }

    public function clear(): void
    {
        $this->connection->createQueryBuilder()
            ->delete('queue')
            ->executeQuery();
    }
}
