<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Service;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoginCommand extends Command
{
    protected static $defaultName = 'login';

    public function __construct(private Service $service)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dto = $this->service->login();

        $this->service->saveCache(
            $dto->getEncKey(),
            $dto->getToken(),
            $dto->getTcpServer()->__toString()
        );

        $output->writeln($dto->asJsonString());

        return self::SUCCESS;
    }
}
