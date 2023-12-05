<?php
declare(strict_types=1);

namespace Zestic\Authentication\Cli;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ResetPasswordCommand extends Command
{
    protected static $defaultName = 'auth:reset-password';

    public function __construct(
        private ContainerInterface $container,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED)
            ->addOption('config', 'c', InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $config = $input->getOption('config') ?? 'users';

        $updatePassword = $this->container->get("{$config}.updatePasswordByUsername");
        $updatePassword->update($username, $password);

        return Command::SUCCESS;
    }
}
