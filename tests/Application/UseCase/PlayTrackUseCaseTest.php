<?php

namespace App\Tests\Application\UseCase;

use App\Application\UseCase\PlayTrackUseCase;
use App\Domain\Queue\QueueStore;
use App\Domain\Queue\TrackAlreadyInQueueException;
use App\Domain\Track\TrackNotFoundException;
use App\Tests\AbstractFunctionalTest;

class PlayTrackUseCaseTest extends AbstractFunctionalTest
{
    public function setUp(): void
    {
        $this->getFromContainer(QueueStore::class)->clear();
    }

    public function testTrackAlreadyInQueueException(): void
    {
        $useCase = $this->getFromContainer(PlayTrackUseCase::class);
        $useCase->execute([1, 2, 4]);

        $this->expectException(TrackAlreadyInQueueException::class);
        $useCase->execute([1]);
    }

    public function testErrorOnDuplicateTracksCall(): void
    {
        $useCase = $this->getFromContainer(PlayTrackUseCase::class);

        $this->expectException(TrackAlreadyInQueueException::class);
        $useCase->execute([1, 2, 3, 1]);
    }

    public function testErrorWhenTractNotFound(): void
    {
        $useCase = $this->getFromContainer(PlayTrackUseCase::class);

        $this->expectException(TrackNotFoundException::class);
        $useCase->execute([99]);
    }
}
