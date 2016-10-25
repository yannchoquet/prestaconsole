<?php
namespace PrestaConsole\Commands\Module;

use PrestaConsole\Helper\ConsoleHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class ModuleUnregisterHookCommand extends Command
{
    private $consoleHelper;

    protected function configure()
    {
        $this
            ->setName('module:unregisterhook')
            ->setDescription('Unregister hook module for all shop')
            ->setHelp("")
            ->addArgument('modules', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'Modules name separate by space')
            ->addOption('id_shop','i', InputOption::VALUE_OPTIONAL, 'Id Shop',null)
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->consoleHelper  = new ConsoleHelper($input, $output);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $id_shop = $input->getOption('id_shop');
        $shop = null;

        if($id_shop){
            $shop[] = \Shop::getShop($id_shop);
            if(!$shop){
                $this->consoleHelper->error($id_shop.' does not exist.');
                return;
            }
        }
        $modules_name = $input->getArgument('modules');
        foreach($modules_name as $module_name){
            if(!\Validate::isModuleName($module_name) || !($module = \Module::getInstanceByName($module_name))){
                $this->consoleHelper->error($module_name.' is not a valid module name.');
                continue;
            }
            if(!$module->id){
                $this->consoleHelper->error($module_name.' is not installed. Please use module:install before.');
                continue;
            }

            /*if(!$module->active){
                $this->consoleHelper->error($module_name.' is not enabled. Please use module:enable before.');
                continue;
            }*/

            $this->consoleHelper->section('Module '.$module->name.': '.$module->displayName);

            $possible_hooks = $module->getPossibleHooksList();


            foreach($possible_hooks as $possible_hook){
                $hooked_module = \Hook::getHookModuleExecList($possible_hook['name']);
                if($this->search($hooked_module, 'module', $module->name ))
                    $hooks_list[$possible_hook['id_hook']] = $possible_hook['name'];
            }

            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion(
                $this->consoleHelper->getQuestion(
                    'Please select the hooks (number separate by comma)',
                    false
                ),
                $hooks_list
            );
            $question->setMultiselect(true);
           // $question->setAutocompleterValues(array_keys($hooks_list));

            $selected_hooks = $helper->ask($input, $output, $question);

           foreach($selected_hooks as $selected_hook){
                if($module->unregisterHook($selected_hook,$shop))
                    $this->consoleHelper->success($module->name.' unhooked on '.$selected_hook);
            }


        }
    }

    public function search($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value)
                $results[] = $array;

        foreach ($array as $subarray)
            $results = array_merge($results, self::search($subarray, $key, $value));

        }

        return $results;
    }
}