<?php
namespace PrestaConsole\Commands\Employee;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use PrestaConsole\Helper\ConsoleHelper;


class EmployeeCreateCommand extends Command
{
    private $consoleHelper;

    protected function configure()
    {
        $this
            ->setName('employee:create')
            ->setDescription('Create Employee')
            ->setDefinition(array(
                new InputOption('id_lang', '', InputOption::VALUE_REQUIRED, 'id_lang'),
                new InputOption('id_profile', '', InputOption::VALUE_REQUIRED, 'id_profile'),
                new InputOption('firstname', '', InputOption::VALUE_REQUIRED, 'Firstname'),
                new InputOption('lastname', '', InputOption::VALUE_REQUIRED, 'Lastname'),
                new InputOption('email', '', InputOption::VALUE_REQUIRED, 'Email'),
                new InputOption('password', '', InputOption::VALUE_REQUIRED, 'Password'),
            ))
            ->setHelp("This command allows you to create employee...")
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->consoleHelper  = new ConsoleHelper($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->createEmployee($input);
        $this->consoleHelper->success('Employee Created!');
    }

    protected function createEmployee(InputInterface $input)
    {

        $employee = new \Employee();
        $employee->firstname = $input->getOption('firstname');
        $employee->lastname = $input->getOption('lastname');
        $employee->email = $input->getOption('email');
        $employee->passwd = $input->getOption('password');
        $employee->id_lang = $input->getOption('id_lang');
        $employee->id_profile = $input->getOption('id_profile');
        $employee->default_tab = 1;
        $employee->save();
    }
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper  = new QuestionHelper();

        $this->consoleHelper->section('Welcome to the Employee generator!');

        /*
         * Lang option
         */
        $available_languages = \Language::getLanguages();
        $default_langugage = \Configuration::get('PS_LANG_DEFAULT');
        $languages = array();
        foreach($available_languages as $l){
            $languages[$l['id_lang']] = $l['name'];
        }
        $question = new ChoiceQuestion(
            $this->consoleHelper->getQuestion(
                'Select a language',
                $languages[$default_langugage]
            ),
            $languages,
            $languages[$default_langugage]
        );
        $question->setErrorMessage('Language %s is invalid.');
        $response = $questionHelper->ask($input, $output, $question);

        $id_lang = array_search ( $response , $languages);
        $input->setOption('id_lang', $id_lang);

        /*
         * Profile option
         */
        $available_profiles = \Profile::getProfiles($id_lang);
        $profiles = array();
        foreach($available_profiles as $p){
            $profiles[$p['id_profile']] = $p['name'];
        }
        $question = new ChoiceQuestion(
            $this->consoleHelper->getQuestion(
                'Select a profile',
                $profiles[1]
            ),
            $profiles,
            1
        );
        $question->setErrorMessage('Profil %s is invalid.');
        $response = $questionHelper->ask($input, $output, $question);

        $id_profile = array_search ( $response , $profiles);
        $input->setOption('id_profile', $id_profile);


        /*
         * Firstname option
         */
        $firstname = $input->getOption('firstname');
        $question = new Question(
            $this->consoleHelper->getQuestion(
                'Firstname?',
                $firstname?$firstname:false
            ), $firstname
        );
        $question->setValidator(function ($answer) {
            if (!\Validate::isName($answer)) {
                throw new \RuntimeException(
                    'Firstname invalid'
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(2);

        $firstname = $questionHelper->ask($input, $output, $question);
        $input->setOption('firstname', $firstname);

        /*
         * Lastname option
         */
        $lastname = $input->getOption('lastname');
        $question = new Question(
            $this->consoleHelper->getQuestion(
                'Lastname?',
                $lastname?$lastname:false
            ), $lastname
        );
        $question->setValidator(function ($answer) {
            if (!\Validate::isName($answer)) {
                throw new \RuntimeException(
                    'Lastname invalid'
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(2);

        $lastname = $questionHelper->ask($input, $output, $question);
        $input->setOption('lastname', $lastname);

        /*
         * Email option
         */
        $email = $input->getOption('email');
        $question = new Question(
            $this->consoleHelper->getQuestion(
                'Email?',
                $email?$email:false
            ), $email
        );
        $question->setValidator(function ($answer) {
            if (!\Validate::isEmail($answer)) {
                throw new \RuntimeException(
                    'Email invalid'
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(2);

        $email = $questionHelper->ask($input, $output, $question);
        $input->setOption('email', $email);

        /*
         * Password option
         */
        $password = $input->getOption('password');
        $default_password = \Tools::passwdGen();
        $question = new Question(
            $this->consoleHelper->getQuestion(
                'Password?',
                $password?$password:$default_password
            ), $password?$password:$default_password
        );
        $question->setValidator(function ($answer) {
            if (!\Validate::isPasswdAdmin($answer)) {
                throw new \RuntimeException(
                    'Password invalid'
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(2);
        $question->setHidden(true);

        $password = $questionHelper->ask($input, $output, $question);
        $input->setOption('password', \Tools::encrypt($password));

        $question = new ConfirmationQuestion($this->consoleHelper->getQuestion('Do you confirm generation', 'yes', '?'), true);
        if (!$questionHelper->ask($input, $output, $question)) {
            $this->consoleHelper->error('Command aborted');
                exit;
        }
    }

}