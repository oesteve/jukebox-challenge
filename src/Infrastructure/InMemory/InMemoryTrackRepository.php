<?php

namespace App\Infrastructure\InMemory;

use App\Domain\Track\Track;
use App\Domain\Track\TrackNotFoundException;
use App\Domain\Track\TrackRepository;

class InMemoryTrackRepository implements TrackRepository
{
    public const TRACKS = [
        ['id' => 1, 'artist' => 'Artist A', 'song' => 'Song 1'],
        ['id' => 2, 'artist' => 'Artist A', 'song' => 'Song 2'],
        ['id' => 3, 'artist' => 'Artist A', 'song' => 'Song 3'],
        ['id' => 4, 'artist' => 'Artist B', 'song' => 'Song 1'],
        ['id' => 5, 'artist' => 'Artist B', 'song' => 'Song 2'],
        ['id' => 6, 'artist' => 'Artist B', 'song' => 'Song 3'],
        ['id' => 7, 'artist' => 'Artist C', 'song' => 'Song 1'],
        ['id' => 8, 'artist' => 'Artist C', 'song' => 'Song 2'],
        ['id' => 9, 'artist' => 'Artist C', 'song' => 'Song 3'],
    ];

    /**
     * @return Track[]
     */
    public function getAllTracks(): array
    {
        return array_map(function ($track) {
            return new Track($track['id'], $track['artist'], $track['song']);
        }, self::TRACKS);
    }

    public function get(int $trackId): Track
    {
        foreach (self::TRACKS as $track) {
            if ($track['id'] === $trackId) {
                return new Track($track['id'], $track['artist'], $track['song']);
            }
        }

        throw new TrackNotFoundException("Track with ID '$trackId' not found");
    }
}
