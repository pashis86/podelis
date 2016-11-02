<?php

namespace NFQ\NFQBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
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
        $crane = $this->getContainer()->get('app.crane');
        $output->write('body - ');
        $output->writeln($crane->getBody());
        $output->write("chassis - ");
        $output->writeln($crane->getChassis());
        $output->write("upperworks - ");
        $output->writeln($crane->getUpperworks());
        $output->writeln('To get original results comment PRE_CREATE event in BikeFactory');
    }
}
