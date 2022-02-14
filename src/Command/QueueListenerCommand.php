<?php

namespace App\Command;

use App\Client\QueueClient;
use App\Entity\Sensor;
use App\Repository\NetworkRepository;
use App\Repository\SensorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'QueueListener',
    description: 'Add a short description for your command',
)]
class QueueListenerCommand extends Command
{
    public function __construct(
        string $name = null,
        protected SensorRepository $sensorRepository,
        protected NetworkRepository $networkRepository,
        protected EntityManagerInterface $em
    )
    {
        parent::__construct($name);
    }
    protected function configure(): void
    {
        $this
            ->addArgument('message', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('queue', InputArgument::REQUIRED, 'Queue name')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $message = $input->getArgument('message');
        $queue = $input->getArgument('queue');

        var_dump($message, $queue);
//        $io->note(sprintf('You passed an argument: %s', $message));

        $msg = json_decode($message);

        var_dump('MSG: ', $message);
        if ((isset($msg->type) && $msg->type == 'incoming') || !isset($msg->id)) {
            return Command::SUCCESS;
        }
        $network = $this->networkRepository->findOneByQueue($queue);
        if (!$network) {
            return Command::SUCCESS;  //INVALID
        }

        $sensor = $this->sensorRepository->getByExternalId($msg->id);
        if (!$sensor) {
            $sensor = new Sensor();
            $sensor->setName($msg->sensorType . ' ' . $msg->id);
            $sensor->setSensorType($msg->sensorType);
            $sensor->setExternalId($msg->id);
            $sensor->setNetwork($network);

        }

        $sensor->setValueFromObject($msg);

        $connections = $sensor->getConnections();
        foreach($connections as $connection) {
            if ($connection->getSensorA()->getId() == $sensor->getId() && $connection->getSensorB()) {
                $queue = $network->getQueue();
                $message = json_encode([
                    'id' => $connection->getSensorB()->getExternalId(),
                    'type' => 'incoming',
                    'network' => $network->getId(),
                    'action' => $connection->getAction(),
                ]);
                var_dump('message', $message);
                $output;
                exec("node publisher.js '$message' '$queue'", $output);
                var_dump('output', $output);
            }
        }

        $this->em->persist($sensor);
        $this->em->flush();

        return Command::SUCCESS;
    }
}
