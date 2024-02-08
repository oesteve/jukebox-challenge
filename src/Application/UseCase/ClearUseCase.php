<?php

namespace App\Application\UseCase;

use App\Domain\Queue\QueueStore;

readonly class ClearUseCase
{
    public function __construct(
        private QueueStore $playlistStore
    ) {
    }

    public function execute(): void
    {
        $this->playlistStore->clear();
    }
}
