<?php
namespace PrestaConsole\Commands\Cache;

use PrestaConsole\Helper\ConsoleHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CacheClearCommand extends Command
{
    private $consoleHelper;

    protected function configure()
    {
        $this
            ->setName('cache:clear')
            ->setDescription('Clear Cache')
            ->setHelp("This command allows you to clear cache...")
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->consoleHelper  = new ConsoleHelper($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        \Tools::clearSmartyCache();
        \Cache::getInstance()->flush();
        \Media::clearCache();

        $this->consoleHelper->success('Cache cleared!');
    }
}