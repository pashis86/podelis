<?php

namespace NFQ\NFQBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
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
        $crane = $this->getContainer()->get('app.crane');
        $output->writeln($crane->getUpperworks());
        $output->writeln('Result.');
    }

}
