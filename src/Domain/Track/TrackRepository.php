<?php

namespace App\Domain\Track;

interface TrackRepository
{
    /**
     * @return Track[]
     */
    public function getAllTracks(): array;

    /**
     * @throws TrackNotFoundException
     */
    public function get(int $trackId): Track;
}
