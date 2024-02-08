<?php

namespace App\Tests\Domain\Queue;

use App\Domain\Queue\Queue;
use App\Domain\Queue\TrackAlreadyInQueueException;
use App\Domain\Track\Track;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    public function testAddTrack(): void
    {
        $queue = new Queue([]);
        $queue->addTrack(new Track(1, 'Artist A', 'Song A'));
        $this->assertCount(1, $queue->getTracks());
    }

    public function testCurrentTrack(): void
    {
        $queue = new Queue([]);

        $firstTrack = new Track(1, 'Artist A', 'Song 1');

        $queue->addTrack($firstTrack);
        $queue->addTrack(new Track(2, 'Artist B', 'Song 1'));
        $queue->addTrack(new Track(3, 'Artist C', 'Song 1'));

        $this->assertEquals($firstTrack, $queue->current());
    }

    public function testGetQueue(): void
    {
        $queue = new Queue([]);

        $firstTrack = new Track(1, 'Artist A', 'Song 1');
        $lastTrack = new Track(3, 'Artist C', 'Song 1');

        $queue->addTrack($firstTrack);
        $queue->addTrack(new Track(2, 'Artist B', 'Song 1'));
        $queue->addTrack($lastTrack);

        $queue = $queue->getTracks();

        $this->assertCount(3, $queue);
        $this->assertEquals($firstTrack, $queue[0]);
        $this->assertEquals($lastTrack, $queue[2]);
    }

    public function testSequentialArtistTracks(): void
    {
        $queue = new Queue([]);
        $queue->addTrack(new Track(1, 'Artist A', 'Song 1'));
        $queue->addTrack(new Track(2, 'Artist B', 'Song 1'));
        $queue->addTrack(new Track(3, 'Artist C', 'Song 1'));
        $queue->addTrack(new Track(4, 'Artist B', 'Song 2'));
        $queue->addTrack(new Track(5, 'Artist A', 'Song 2'));
        $queue->addTrack(new Track(6, 'Artist C', 'Song 2'));

        $tracks = array_map(
            fn (Track $track) => (string) $track,
            $queue->getTracks()
        );

        $this->assertEquals([
            '[1] Artist A - Song 1',
            '[5] Artist A - Song 2',
            '[2] Artist B - Song 1',
            '[4] Artist B - Song 2',
            '[3] Artist C - Song 1',
            '[6] Artist C - Song 2',
        ], $tracks);
    }

    public function testErrorIfTrackIsAlreadyInTheQueue(): void
    {
        $queue = new Queue([]);

        $firstTrack = new Track(1, 'Artist A', 'Song 1');
        $queue->addTrack($firstTrack);
        $queue->addTrack(new Track(2, 'Artist B', 'Song 1'));
        $queue->addTrack(new Track(3, 'Artist C', 'Song 1'));

        $this->expectException(TrackAlreadyInQueueException::class);
        $this->expectExceptionMessage("Track '[1] Artist A - Song 1' is already in the queue.");

        $queue->addTrack($firstTrack);
    }
}
