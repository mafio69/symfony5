<?php

namespace App\Command;

use App\Entity\User;
use App\Services\InspectionServices;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddUserFromCSV extends Command
{
    protected static $defaultName = 'app:addUserFromCSV';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var InspectionServices
     */
    private $inspectionServices;

    /**
     * @param InspectionServices $inspectionServices
     */
    public function __construct(InspectionServices $inspectionServices)
    {
        parent::__construct();
        $this->inspectionServices = $inspectionServices;
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setDescription('Import CSV')
            ->setHelp('This command read CSV and save DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Attempting import of Feed...');
        $reader = Reader::createFromPath('%kernel.root_dir%/../Data/CSV/users.csv');

        try {
            $reader->setHeaderOffset(0);
        } catch (Exception $e) {
            $this->inspectionServices->logger->error($e->getMessage());
            $io->error('File error' . $e->getMessage());

            return Command::FAILURE;
        }

        $result = $this->inspectionServices->createUser($reader);

        if ($result === true) {
            $io->success('Command exited cleanly!');
            $this->inspectionServices->logger->info('User add from CSV file.');
            return Command::SUCCESS;
        }

        $io->error('User save to database error');

        return Command::FAILURE;
    }
}