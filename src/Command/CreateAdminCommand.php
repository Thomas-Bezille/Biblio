<?php

namespace App\Command;

use App\Service\CreateAdminService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Créer un nouvel administrateur',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly CreateAdminService $createAdminService
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l\'administrateur à créer')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe de l\'administrateur à créer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $this->createAdminService->createAdmin($email, $password);

        $io->success('L\'administrateur a été créé avec succès !');

        return Command::SUCCESS;
    }
}
