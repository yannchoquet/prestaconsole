<?php
namespace PrestaConsole\Commands\Cache;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class CacheCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('cache:clear')
            ->setDescription('Clear Cache')
            ->setHelp("This command allows you to clear cache...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        \Tools::clearSmartyCache();
        \Cache::getInstance()->flush();
        \Media::clearCache();

        $io->success('Cache cleared!');
    }
}