<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use JsonMachine\Items;
use JsonMachine\JsonDecoder\DecodingError;
use JsonMachine\JsonDecoder\ErrorWrappingDecoder;
use JsonMachine\JsonDecoder\ExtJsonDecoder;
use App\Entity\Flats;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:sync-flats',
    description: 'Refresh flats database.',
    hidden: false,
    aliases: ['app:sync-flats']
)]
class SyncFlatsCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    /**
     * Executes the command to sync flats data.
     *
     * @param InputInterface  $input  The input interface.
     * @param OutputInterface $output The output interface.
     *
     * @return int The command exit code.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Fetch flats data from URL
        $flats = Items::fromFile('{json_url}');

        // Process each flat and add it to the database
        foreach ($flats as $id => $flat) {
            // Create a new flat entity and set its properties
            $flatEntity = new Flats();
            $flatEntity->setImg($flat->image_0);
            $flatEntity->setCity($flat->city);
            $flatEntity->setName($flat->title_es);
            $flatEntity->setDescription($flat->description_es);

            // Persist the flat entity
            $this->entityManager->persist($flatEntity);
            $this->entityManager->flush();
        }

        // Output success message
        $io->success('Completed');

        return Command::SUCCESS;
    }
}
