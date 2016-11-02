<?php

namespace NFQ\SandboxBundle\Command;

use NFQ\SandboxBundle\Service\Bike;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppDebugCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:debug')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bike = $this->getContainer()->get('app.bike');

        $output->writeln($bike->getFrame());
        $output->writeln('Result.');
    }

}
