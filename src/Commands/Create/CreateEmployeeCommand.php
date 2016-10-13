<?php
namespace PrestaConsole\Commands\Create;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class CreateEmployeeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('create:employee')
            ->setDescription('Create Employee')
            ->setHelp("This command allows you to create employee...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $employee = new \Employee();
        var_dump($employee);

        $io->success('Employee Created!');
    }
}