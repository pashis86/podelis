<?php

namespace NFQ\SandboxBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppCliCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
          ->setName('app:cli')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bike = $this->getContainer()->get('app.bike');

        $output->write('Frame - ');
        $output->writeln($bike->getFrame());
        $output->write("Wheels - ");
        $output->writeln($bike->getWheels());
        $output->write("Body - ");
        $output->writeln($bike->getBody());
        $output->writeln('To get original results comment PRE_CREATE event in BikeFactory');
    }

}
