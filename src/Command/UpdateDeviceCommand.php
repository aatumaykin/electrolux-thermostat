<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Electrolux\Helper\Json;
use App\Service\Service;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDeviceCommand extends Command
{
    protected static $defaultName = 'update-device';

    public function __construct(private Service $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Update params of device')
            ->addArgument('deviceId', InputArgument::REQUIRED, 'Device ID')
            ->addArgument('params', InputArgument::REQUIRED, 'Params in json format')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $deviceId = (string) $input->getArgument('deviceId');
        $params = Json::decodeAsArray((string) $input->getArgument('params'));

        $data = $this->service->setDeviceUpdate($deviceId, $params);

        $output->writeln(Json::encode($data));

        return self::SUCCESS;
    }
}
