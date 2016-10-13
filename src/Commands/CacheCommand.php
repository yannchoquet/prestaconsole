<?php
namespace PrestaConsole\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PrestaCore;

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
        var_dump(Tools::getValue('popo'));
        /*\Tools::clearSmartyCache();
        \CacheFs::deleteCacheDirectory();*/
    }
}