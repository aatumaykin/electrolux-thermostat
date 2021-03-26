<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Electrolux\Service\CryptService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DecodeCommand extends Command
{
    protected static $defaultName = 'decode';

    protected function configure(): void
    {
        $this
            ->setDescription('Decode message')
            ->addArgument('key', InputArgument::REQUIRED, 'Key')
            ->addArgument('message', InputArgument::REQUIRED, 'Message')
            ->addOption('hex', null, InputOption::VALUE_NONE, 'Message in hex format')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = (string) $input->getArgument('key');
        $message = (string) $input->getArgument('message');
        $useHex = (bool) $input->getOption('hex');

        if ($useHex) {
            $message = hex2bin($message);
        }
        $message = (new CryptService($key))->decrypt($message);

        $output->writeln($message);

        return self::SUCCESS;
    }
}
