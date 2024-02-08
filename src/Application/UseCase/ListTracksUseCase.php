<?php

namespace App\Application\UseCase;

use App\Domain\Track\Track;
use App\Domain\Track\TrackRepository;

readonly class ListTracksUseCase
{
    public function __construct(
        private TrackRepository $trackRepository
    ) {
    }

    /**
     * @return array<int, Track>
     */
    public function execute(): array
    {
        return $this->trackRepository->getAllTracks();
    }
}
