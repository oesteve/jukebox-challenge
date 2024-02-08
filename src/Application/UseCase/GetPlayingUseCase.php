<?php

namespace App\Application\UseCase;

use App\Domain\Queue\QueueStore;
use App\Domain\Track\Track;

readonly class GetPlayingUseCase
{
    public function __construct(
        private QueueStore $playlistStore
    ) {
    }

    public function execute(): ?Track
    {
        return $this->playlistStore->get()->current();
    }
}
