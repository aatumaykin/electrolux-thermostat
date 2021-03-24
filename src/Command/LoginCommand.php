<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoginCommand extends Command
{
    protected static $defaultName = 'login';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('test');

        return self::SUCCESS;
    }
}
