<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Service;
use JsonException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMessageCommand extends Command
{
    protected static $defaultName = 'send-message';

    public function __construct(private Service $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send message from text file')
            ->addArgument('file', InputArgument::REQUIRED, 'file')
        ;
    }

    /**
     * @throws JsonException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = (string) $input->getArgument('file');

        $this->service->sendMessageFromFile($file);

        return self::SUCCESS;
    }
}
