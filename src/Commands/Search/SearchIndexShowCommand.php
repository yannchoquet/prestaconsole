<?php
namespace PrestaConsole\Commands\Search;

use PrestaConsole\Helper\ConsoleHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;


class SearchIndexShowCommand extends Command
{
    private $consoleHelper;

    protected function configure()
    {
        $this
            ->setName('search:index:show')
            ->setDescription('Show index Search')
            ->setHelp("This command allows you to show index search...")
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->consoleHelper  = new ConsoleHelper($input, $output);
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list($total, $indexed) = \Db::getInstance()->getRow('SELECT COUNT(*) as "0", SUM(product_shop.indexed) as "1" FROM '.\_DB_PREFIX_.'product p '.\Shop::addSqlAssociation('product', 'p').' WHERE product_shop.`visibility` IN ("both", "search") AND product_shop.`active` = 1');

        $this->consoleHelper->note('Indexed products'.' '.(int)$indexed.' / '.(int)$total);
    }
}