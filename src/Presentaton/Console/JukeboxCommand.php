<?php

namespace App\Presentaton\Console;

use App\Application\UseCase\ClearUseCase;
use App\Application\UseCase\GetPlayingUseCase;
use App\Application\UseCase\GetQueueUseCase;
use App\Application\UseCase\ListTracksUseCase;
use App\Application\UseCase\PlayTrackUseCase;
use App\Domain\Queue\TrackAlreadyInQueueException;
use App\Domain\Track\TrackNotFoundException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'jukebox',
    description: 'Jukebox command'
)]
class JukeboxCommand extends Command
{
    public const list = 'list';
    public const play = 'play';
    public const queue = 'queue';
    public const playing = 'playing';
    public const clear = 'clear';
    public const exit = 'exit';

    public function __construct(
        private readonly GetPlayingUseCase $getPlayingUseCase,
        private readonly ListTracksUseCase $listTracksUseCase,
        private readonly PlayTrackUseCase $playTrackUseCase,
        private readonly GetQueueUseCase $listPlaylistUseCase,
        private readonly ClearUseCase $clearUseCase
    ) {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('.:: Jukebox Challenge 🔈 ::.');

        while (true) {
            $selectedAction = $io->choice('Type or choose an option ↕️', [
                self::list => 'List available tracks',
                self::play => 'Play a track',
                self::queue => 'Show the track queue',
                self::playing => 'Show the currently playing track',
                self::clear => 'Clear the queue',
                self::exit => 'Exit',
            ]);

            switch ($selectedAction) {
                case self::list:
                    $this->listAvailableTracks($io);
                    break;

                case self::play:
                    $this->playTracks($io);
                    break;

                case self::queue:
                    $this->printQueue($io);
                    break;

                case self::playing:
                    $this->displayPlayingTrack($io);
                    break;

                case self::clear:
                    $this->clearQueue($io);
                    break;

                case self::exit:
                    $io->success('Goodbye! 👋🏼');

                    return Command::SUCCESS;
            }
        }
    }

    private function printQueue(SymfonyStyle $io): void
    {
        $this->clearTerminal($io);
        $elements = $this->listPlaylistUseCase->execute();

        if (empty($elements)) {
            $io->note('No song is currently playing.');

            return;
        }

        $io->title('Songs in the queue');
        $io->listing($elements);
    }

    /**
     * @return int[]
     */
    private function getTracksToPlay(SymfonyStyle $io): array
    {
        $tracks = $this->listTracksUseCase->execute();

        $io->title('Choose a track to play');
        $io->listing($tracks);
        $input = $io->ask('Which track do you want to play?  <num>[,<num>...]');

        return array_map('intval', explode(',', $input));
    }

    private function listAvailableTracks(SymfonyStyle $io): void
    {
        $this->clearTerminal($io);
        $io->title('Available Songs');
        $io->listing($this->listTracksUseCase->execute());
    }

    private function clearQueue(SymfonyStyle $io): void
    {
        $this->clearTerminal($io);
        $io->warning('Cleared queue.');
        $this->clearUseCase->execute();
    }

    private function playTracks(SymfonyStyle $io): void
    {
        $this->clearTerminal($io);
        $tracks = $this->getTracksToPlay($io);

        try {
            $this->playTrackUseCase->execute($tracks);
        } catch (TrackAlreadyInQueueException|TrackNotFoundException $e) {
            $io->error($e->getMessage());

            return;
        }

        $io->success('Tracks have been added to the queue.');
        $this->printQueue($io);
    }

    private function displayPlayingTrack(SymfonyStyle $io): void
    {
        $this->clearTerminal($io);
        $track = $this->getPlayingUseCase->execute();
        if (!$track) {
            $io->note('No song is currently playing.');

            return;
        }

        $this->clearTerminal($io);
        $io->title('Currently playing');
        $io->block('▶️ Playing track: '.$track);
        $io->newLine();
    }

    private function clearTerminal(SymfonyStyle $io): void
    {
        $io->write(sprintf("\033\143"));
    }
}
