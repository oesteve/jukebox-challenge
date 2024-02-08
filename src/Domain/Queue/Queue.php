<?php

namespace App\Domain\Queue;

use App\Domain\Track\Track;

class Queue
{
    /**
     * @var array<int,Track>
     */
    private array $queue;

    /**
     * @param Track[] $tracks
     */
    public function __construct(array $tracks)
    {
        $this->queue = $tracks;
    }

    /**
     * @throws TrackAlreadyInQueueException
     */
    public function addTrack(Track $track): void
    {
        foreach ($this->queue as $queuedTrack) {
            if ($queuedTrack->getId() === $track->getId()) {
                throw new TrackAlreadyInQueueException("Track '$track' is already in the queue.");
            }
        }

        for ($i = count($this->queue); $i > 0; --$i) {
            if ($this->queue[$i - 1]->getArtist() === $track->getArtist()) {
                array_splice($this->queue, $i, 0, [$track]);

                return;
            }
        }

        $this->queue[] = $track;
    }

    /**
     * @return Track[]
     */
    public function getTracks(): array
    {
        return array_values($this->queue);
    }

    public function current(): ?Track
    {
        $first = reset($this->queue);

        return (false === $first) ? null : $first;
    }
}
