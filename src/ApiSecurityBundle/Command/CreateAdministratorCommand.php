<?php
namespace ApiSecurityBundle\Command;

use ApiBundle\Entity\ApiKey;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdministratorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('api:security:create-admin')
            ->setDescription('Creates a User with role Administrator')
            ->addArgument('username', InputArgument::REQUIRED, 'Administrator username')
            ->addArgument('email', InputArgument::REQUIRED, 'Administrator E-mail')
            ->addOption(
                'password',
                null,
                InputOption::VALUE_OPTIONAL,
                'Administrator password (default: 123456)'
            )
            ->addOption(
                'skip-api-key',
                null,
                InputOption::VALUE_NONE,
                'disables API KEY generation for this administrator'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');

        $password = $input->getOption('password');

        if (!$password) {
            $password = '123456';
        }

        $skipApiKey = $input->getOption('skip-api-key');

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $keyGenerator = $this->getContainer()->get('api_security.api_key_generator');
        $manipulator = $this->getContainer()->get('fos_user.util.user_manipulator');
        $expirationDays = $this->getContainer()->getParameter('api_security.expiration_days');

        $user = $manipulator->create($email, $password, $email, true, false);
        $user->addRole('ROLE_API');
        $user->addRole('ROLE_ADMIN');

        if (!$skipApiKey) {
            $apiKey = new ApiKey();
            if ($username === 'app') {
                $apiKey->setAccessKey('ede69066-c41b-41e3-851b-5d5acdd8d295');
            } else {
                $apiKey->setAccessKey($keyGenerator->generate());
            }
            $apiKey->setRefreshKey($keyGenerator->generate());

            $now = new \DateTimeImmutable();

            $apiKey->setCreatedAt($now);
            $apiKey->setExpiresAt($now->add(new \DateInterval(sprintf('P%dD', $expirationDays))));
            $apiKey->setUser($user);
            $user->setApiKey($apiKey);
            $em->persist($apiKey);
        }

        $em->persist($user);
        $em->flush();

        $output->writeln(sprintf('Created admin <comment>%s</comment>', $username));
    }
}
