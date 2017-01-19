<?php

namespace AppBundle\Command;


use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use AppBundle\Security\UserManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends ContainerAwareCommand
{
	/**
	 * @var UserManager
	 */
	protected $userManager;

	/**
	 *
	 * @param UserManager $userManager
	 */
	public function setUserManager(UserManager $userManager) {
		$this->userManager = $userManager;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this
			->setName('user:create')
			->setDescription('Create a user.')
			->setDefinition(array(
				new InputArgument('username', InputArgument::REQUIRED, 'The username'),
				new InputArgument('email', InputArgument::REQUIRED, 'The email'),
				new InputArgument('password', InputArgument::REQUIRED, 'The password'),
				new InputOption('admin', null, InputOption::VALUE_NONE, 'Set the user as admin'),
				new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
			))
			->setHelp(<<<'EOT'
The <info>fos:user:create</info> command creates a user:

  <info>php %command.full_name% matthieu</info>

This interactive shell will ask you for an email and then a password.

You can alternatively specify the email and password as the second and third arguments:

  <info>php %command.full_name% matthieu matthieu@example.com mypassword</info>

You can create a super admin via the admin flag:

  <info>php %command.full_name% admin --admin</info>

You can create an inactive user (will not be able to log in):

  <info>php %command.full_name% thibault --inactive</info>

EOT
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->userManager->createUser(
			$input->getArgument('username'),
			$input->getArgument('email'),
			$input->getArgument('password'),
			!((bool) $input->getOption('inactive')),
			(bool) $input->getOption('admin')
		);

		$output->writeln(sprintf('Created user <comment>%s</comment>', $input->getArgument('username')));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function interact(InputInterface $input, OutputInterface $output)
	{
		$questions = array();

		if (!$input->getArgument('username')) {
			$question = new Question('Please choose a username:');
			$question->setValidator(function ($username) {
				if (empty($username)) {
					throw new \Exception('Username can not be empty');
				}

				return $username;
			});
			$questions['username'] = $question;
		}

		if (!$input->getArgument('email')) {
			$question = new Question('Please choose an email:');
			$question->setValidator(function ($email) {
				if (empty($email)) {
					throw new \Exception('Email can not be empty');
				}

				return $email;
			});
			$questions['email'] = $question;
		}

		if (!$input->getArgument('password')) {
			$question = new Question('Please choose a password:');
			$question->setValidator(function ($password) {
				if (empty($password)) {
					throw new \Exception('Password can not be empty');
				}

				return $password;
			});
			$question->setHidden(true);
			$questions['password'] = $question;
		}

		foreach ($questions as $name => $question) {
			$answer = $this->getHelper('question')->ask($input, $output, $question);
			$input->setArgument($name, $answer);
		}
	}
}
