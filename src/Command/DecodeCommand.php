<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Electrolux\Helper\CleanHelper;
use App\Domain\Electrolux\Service\CryptService;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function dirname;
use function file_get_contents;
use function hex2bin;

class DecodeCommand extends Command
{
    protected static $defaultName = 'decode';

    private CryptService $service;

    protected function configure(): void
    {
        $this
            ->setDescription('Расшифровка сообщения из параметра либо файла')
            ->addArgument('key', InputArgument::REQUIRED, 'Ключ')
            ->addArgument('message', InputArgument::OPTIONAL, 'Сообщение')
            ->addOption('file', null, InputOption::VALUE_OPTIONAL, 'Файл для вставки сообщений')
            ->addOption('hex', null, InputOption::VALUE_NONE, 'Если сообщение в hex формате')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = (string) $input->getArgument('key');
        $message = (string) $input->getArgument('message');
        $file = $input->getOption('file');
        $useHex = (bool) $input->getOption('hex');

        if (null === $file && '' === $message) {
            throw new RuntimeException('Не передан аргумент message или опция --file');
        }

        $this->service = new CryptService($key);

        if (null !== $file) {
            $this->fromFile($file, $output, $useHex);
        }

        $this->fromMessage($message, $output, $useHex);

        return self::SUCCESS;
    }

    private function fromMessage(string $message, OutputInterface $output, bool $useHex = false): void
    {
        $message = CleanHelper::clean($message);

        if ($useHex) {
            $message = hex2bin($message);
        }

        $message = $this->service->decrypt($message);

        $output->writeln($message);
        $output->writeln('');
    }

    private function fromFile(string $file, OutputInterface $output, bool $useHex = false): void
    {
        $prev = null;
        while (true) {
            $message = (string) file_get_contents($file);
            $message = CleanHelper::clean($message);

            if ('' === $message || $prev === $message) {
                continue;
            }

            $prev = $message;

            if ($useHex) {
                $message = hex2bin($message);
            }

            $message = $this->service->decrypt($message);

            $output->writeln($message);
            $output->writeln('');
        }
    }
}
