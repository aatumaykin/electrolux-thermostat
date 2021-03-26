<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Electrolux\Helper\Json;
use App\Service\Service;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetDevicesCommand extends Command
{
    protected static $defaultName = 'get-devices';

    public function __construct(private Service $service)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->service->getDevices();

        $output->writeln(Json::encode($data));

        return self::SUCCESS;
    }
}
