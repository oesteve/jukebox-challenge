<?php

namespace App\Application\UseCase;

use App\Domain\Queue\QueueStore;
use App\Domain\Queue\TrackAlreadyInQueueException;
use App\Domain\Track\TrackNotFoundException;
use App\Domain\Track\TrackRepository;

readonly class PlayTrackUseCase
{
    public function __construct(
        private TrackRepository $trackRepository,
        private QueueStore $playlistStore,
    ) {
    }

    /**
     * @param array<int> $tracks
     *
     * @throws TrackAlreadyInQueueException
     * @throws TrackNotFoundException
     */
    public function execute(array $tracks): void
    {
        $playlist = $this->playlistStore->get();

        foreach ($tracks as $trackId) {
            $track = $this->trackRepository->get($trackId);
            $playlist->addTrack($track);
        }

        $this->playlistStore->save($playlist);
    }
}
