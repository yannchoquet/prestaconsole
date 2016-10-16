<?php
namespace PrestaConsole\Commands\Module;

use PrestaConsole\Helper\ConsoleHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleEnableCommand extends Command
{
    private $consoleHelper;

    protected function configure()
    {
        $this
            ->setName('module:enable')
            ->setDescription('Enable module for all shop')
            ->setHelp("")
            ->addArgument('modules', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'Modules name separate by space')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->consoleHelper  = new ConsoleHelper($input, $output);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modules_name = $input->getArgument('modules');
        foreach($modules_name as $module_name){
            if(!\Validate::isModuleName($module_name) || !($module = \Module::getInstanceByName($module_name))){
                $this->consoleHelper->error($module_name.' is not a valid module name.');
                continue;
            }

            if($module->id && $module->enable())
                $this->consoleHelper->success($module_name.' activated');
            else
                $this->consoleHelper->error($module_name.' is not installed. Please use module:install before.');
        }
    }
}