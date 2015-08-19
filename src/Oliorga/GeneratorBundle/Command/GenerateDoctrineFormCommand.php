<?php

namespace Oliorga\GeneratorBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Oliorga\GeneratorBundle\Generator\DoctrineFormGenerator;


class GenerateDoctrineFormCommand extends \Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineFormCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        parent::configure();
        $this->setAliases(array('oliorga:generate:form'));
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle).'\\'.$entity;
        $metadata = $this->getEntityMetadata($entityClass);
        $bundle   = $this->getApplication()->getKernel()->getBundle($bundle);

        $generator = new \Oliorga\GeneratorBundle\Generator\DoctrineFormGenerator($this->getContainer()->get('filesystem'));
        $generator->setSkeletonDirs($this->getContainer()->get('kernel')->locateResource('@OliorgaGeneratorBundle/Resources/skeleton'));
        $generator->generate($bundle, $entity, $metadata[0]);
        
        $output->writeln(sprintf(
            'The new %s.php class file has been created under %s.',
            $generator->getClassName(),
            $generator->getClassPath()
        ));
    }

    protected function createGenerator()
    {
        return new \Oliorga\GeneratorBundle\Generator\DoctrineFormGenerator($this->getContainer()->get('filesystem'));
    }
}
