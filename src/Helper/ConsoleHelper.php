<?php
namespace PrestaConsole\Helper;

use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleHelper extends Helper
{
    protected $output;
    protected $io;
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);
    }

    public function getQuestion($question, $default, $sep = ':')
    {
        return $default ? sprintf('<info>%s</info> [<comment>%s</comment>]%s ', $question, $default, $sep) : sprintf('<info>%s</info>%s ', $question, $sep);
    }

    public function section($text)
    {
        $this->io->block($text, null, 'bg=blue;fg=white', ' ', true);
    }

    public function success($text)
    {
        $this->io->success($text);
    }

    public function error($text)
    {
        $this->io->error($text);
    }



    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'consolehelper';
    }
}