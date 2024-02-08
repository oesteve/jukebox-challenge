<?php

namespace App\Domain\Queue;

interface QueueStore
{
    public function get(): Queue;

    public function save(Queue $queue): void;

    public function clear(): void;
}
