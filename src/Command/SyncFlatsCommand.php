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

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input,OutputInterface $output         ): int
    {
        $io = new SymfonyStyle($input, $output);
        //$arg1 = $input->getArgument('arg1');

        $flats = Items::fromFile('http://feeds.spotahome.com/main.json');

        echo '<pre>';
        
        foreach ($flats as $id => $flat) {

            // var_dump($id);
            // var_dump($flat);
            //     var_dump($flat->description_es);
            // var_dump($flat->title_es);
        $flat_entity = new Flats();
        $flat_entity->setImg($flat->image_0);
        $flat_entity->setName($flat->title_es);

        $flat_entity->setDescription($flat->description_es);

        $this->entityManager->persist($flat_entity);
        $this->entityManager->flush();
            // just process $user as usual
       //     die('test');
            echo $flat->id.PHP_EOL;
        }
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
