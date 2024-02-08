<?php

namespace App\Domain\Track;

class Track implements \Stringable
{
    public function __construct(
        private int $id,
        private string $artist,
        private string $song
    ) {
    }

    public function __toString(): string
    {
        return sprintf('[%d] %s - %s', $this->id, $this->artist, $this->song);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getArtist(): string
    {
        return $this->artist;
    }

    public function getSong(): string
    {
        return $this->song;
    }
}
