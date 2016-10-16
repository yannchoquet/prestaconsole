<?php
namespace PrestaConsole\Commands\Module;

use PrestaConsole\Helper\ConsoleHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleListCommand extends Command
{
    private $consoleHelper;

    protected function configure()
    {
        $this
            ->setName('module:list')
            ->setDescription('Show all Modules')
            ->setHelp("")
            ->addArgument('args', InputArgument::IS_ARRAY, 'Who do you want to greet?')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->consoleHelper  = new ConsoleHelper($input, $output);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modules = \Module::getModulesOnDisk();
        $args = $input->getArgument('args');
        $modules_list = array();

        $valid_args = array('active', 'unactive', 'installed', 'uninstalled');
        foreach($args as $arg){
            if(!in_array($arg, $valid_args)){
                $this->consoleHelper->error($arg.' is not a valid argument. Use active, unactive, installed, uninstalled');
            }
        }
        foreach($modules as $k => $module){
            $active_statut = $module->active?'active':'unactive';
            $installed_statut =  $module->installed?'installed':'uninstalled';
            if(!$args || (in_array($active_statut, $args) || in_array($installed_statut, $args))){
                $modules_list[$k]['name'] = $module->name;
                $modules_list[$k]['display_name'] = $module->displayName;
                $modules_list[$k]['version'] = $module->version;
                $modules_list[$k]['installed'] = $active_statut;
                $modules_list[$k]['active'] = $installed_statut;
            }
        }
        usort($modules_list, "self::sortResults");

        $this->consoleHelper->section(count($modules_list).' Modules');

        $table = new Table($output);

        $table
            ->setHeaders(array('Name', 'Display Name', 'Version', 'Installed', 'Active'))
            ->setRows($modules_list)
        ;
        $table->render();
    }

    private static function sortResults($a,$b) {
        return strcmp($a["name"], $b["name"]);
    }
}