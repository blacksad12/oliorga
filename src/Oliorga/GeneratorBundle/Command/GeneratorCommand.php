<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Oliorga\GeneratorBundle\Command;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Oliorga\GeneratorBundle\Command\Helper\QuestionHelper;
use Oliorga\GeneratorBundle\Generator\Generator;

/**
 * Base class for generator commands.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class GeneratorCommand extends ContainerAwareCommand
{
    private $generator;

    // only useful for unit tests
    public function setGenerator(Generator $generator)
    {
        $this->generator = $generator;
    }

    protected abstract function createGenerator();

    protected function getGenerator(BundleInterface $bundle = null)
    {
        if (null === $this->generator) {
            $this->generator = $this->createGenerator();
            $this->generator->setSkeletonDirs($this->getSkeletonDirs($bundle));
        }

        return $this->generator;
    }

    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();

        if (isset($bundle) && is_dir($dir = $bundle->getPath().'/Resources/SensioGeneratorBundle/skeleton')) {
            $skeletonDirs[] = $dir;
        }

        if (is_dir($dir = $this->getContainer()->get('kernel')->getRootdir().'/Resources/SensioGeneratorBundle/skeleton')) {
            $skeletonDirs[] = $dir;
        }

        $skeletonDirs[] = __DIR__.'/../Resources/skeleton';
        $skeletonDirs[] = __DIR__.'/../Resources';

        return $skeletonDirs;
    }

    protected function getQuestionHelper()
    {
        $questionHelper = $this->getHelperSet()->get('dialog');
        if (!$questionHelper || get_class($questionHelper) !== 'Oliorga\GeneratorBundle\Command\Helper\QuestionHelper') {
            $this->getHelperSet()->set($questionHelper = new QuestionHelper());
        }

        return $questionHelper;
    }
}
