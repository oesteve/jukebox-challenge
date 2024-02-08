<?php

namespace App\Application\UseCase;

use App\Domain\Queue\QueueStore;
use App\Domain\Track\Track;

readonly class GetQueueUseCase
{
    public function __construct(
        private QueueStore $playlistStore
    ) {
    }

    /**
     * @return array<Track>
     */
    public function execute(): array
    {
        return $this->playlistStore->get()->getTracks();
    }
}
