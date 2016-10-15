<?php
namespace PrestaConsole\Commands\Search;

use PrestaConsole\Helper\ConsoleHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;


class SearchIndexCommand extends Command
{
    private $consoleHelper;

    protected function configure()
    {
        $this
            ->setName('search:index')
            ->setDescription('Reindex Search')
            ->setHelp("This command allows you to index search...")
            ->setDefinition(array(
                new InputOption('full','', InputOption::VALUE_NONE, 'Full'),
            ))
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->consoleHelper  = new ConsoleHelper($input, $output);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $full = $input->getOption('full');
        if(\Search::indexation($full))
            $this->consoleHelper->success('Search reindexed!');
    }
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper  = new QuestionHelper();
        /*
         * Full option
         */
        $full = $input->getOption('full');
        $question = new ConfirmationQuestion(
            $this->consoleHelper->getQuestion(
                'Rebuild full index?',
                $full?"yes":"no"
            ), $full?true:false);

        $full = $questionHelper->ask($input, $output, $question);
        $input->setOption('full', $full);
    }
}