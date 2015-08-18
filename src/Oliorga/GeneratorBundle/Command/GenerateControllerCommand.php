<?php

namespace Oliorga\GeneratorBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Oliorga\GeneratorBundle\Command\Helper\QuestionHelper;
use Oliorga\GeneratorBundle\Generator\ControllerGenerator;

/**
 * Generates controllers.
 */
class GenerateControllerCommand extends \Sensio\Bundle\GeneratorBundle\Command\GenerateControllerCommand
{
    /**
     * @see Command
     */
    public function configure() {
        parent::configure();
        $this->setAliases(array('app:generate:controller'));
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getQuestionHelper();

        if ($input->isInteractive()) {
            if (!$dialog->askConfirmation($output, $dialog->getQuestion('Do you confirm generation', 'yes', '?'), true)) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }

        if (null === $input->getOption('controller')) {
            throw new \RuntimeException('The controller option must be provided.');
        }

        list($bundle, $controller) = $this->parseShortcutNotation($input->getOption('controller'));
        if (is_string($bundle)) {
            $bundle = Validators::validateBundleName($bundle);

            try {
                $bundle = $this->getContainer()->get('kernel')->getBundle($bundle);
            } catch (\Exception $e) {
                $output->writeln(sprintf('<bg=red>Bundle "%s" does not exist.</>', $bundle));
            }
        }
        
        $dialog->writeSection($output, 'Controller generation');

        $generator = $this->getGenerator($bundle);
        $generator->setSkeletonDirs($this->getContainer()->get('kernel')->locateResource('@AppGeneratorBundle/Resources/skeleton'));
        $generator->generate($bundle, $controller, $input->getOption('route-format'), $input->getOption('template-format'), $this->parseActions($input->getOption('actions')));

        $output->writeln('Generating the bundle code: <info>OK</info>');

        $dialog->writeGeneratorSummary($output, array());
    }

    public function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getQuestionHelper();
        $dialog->writeSection($output, 'Welcome to the Symfony2 controller generator');

        // namespace
        $output->writeln(array(
            '',
            'Every page, and even sections of a page, are rendered by a <comment>controller</comment>.',
            'This command helps you generate them easily.',
            '',
            'First, you need to give the controller name you want to generate.',
            'You must use the shortcut notation like <comment>AcmeBlogBundle:Post</comment>',
            '',
        ));

        $bundleNames = array_keys($this->getContainer()->get('kernel')->getBundles());
        while (true) {
            $controller = $dialog->askAndValidate($output, $dialog->getQuestion('Controller name', $input->getOption('controller')), array('Oliorga\GeneratorBundle\Command\Validators', 'validateControllerName'), false, $input->getOption('controller'), $bundleNames);
            list($bundle, $controller) = $this->parseShortcutNotation($controller);

            try {
                $b = $this->getContainer()->get('kernel')->getBundle($bundle);

                if (!file_exists($b->getPath().'/Controller/'.$controller.'Controller.php')) {
                    break;
                }

                $output->writeln(sprintf('<bg=red>Controller "%s:%s" already exists.</>', $bundle, $controller));
            } catch (\Exception $e) {
                $output->writeln(sprintf('<bg=red>Bundle "%s" does not exist.</>', $bundle));
            }
        }
        $input->setOption('controller', $bundle.':'.$controller);

        // routing format
        $routeFormat = 'annotation';;
        $input->setOption('route-format', $routeFormat);

        // templating format
        $templateFormat = 'twig';
        $input->setOption('template-format', $templateFormat);

        // actions
        $input->setOption('actions', $this->addActions($input, $output, $dialog));

        // summary
        $output->writeln(array(
            '',
            $this->getHelper('formatter')->formatBlock('Summary before generation', 'bg=blue;fg-white', true),
            '',
            sprintf('You are going to generate a "<info>%s:%s</info>" controller', $bundle, $controller),
            sprintf('using the "<info>%s</info>" format for the routing and the "<info>%s</info>" format', $routeFormat, $templateFormat),
            'for templating',
        ));
    }

    public function addActions(InputInterface $input, OutputInterface $output, \Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper $dialog)
    {
        $output->writeln(array(
            '',
            'Instead of starting with a blank controller, you can add some actions now. An action',
            'is a PHP function or method that executes, for example, when a given route is matched.',
            'Actions should be suffixed by <comment>Action</comment>.',
            '',
        ));

        $templateNameValidator = function($name) {
            if ('default' == $name) {
                return $name;
            }

            if (2 != substr_count($name, ':')) {
                throw new \InvalidArgumentException(sprintf('Template name "%s" does not have 2 colons', $name));
            }

            return $name;
        };

        $actions = $this->parseActions($input->getOption('actions'));

        while (true) {
            // name
            $output->writeln('');
            $actionName = $dialog->askAndValidate($output, $dialog->getQuestion('New action name (press <return> to stop adding actions)', null), function ($name) use ($actions) {
                if (null == $name) {
                    return $name;
                }

                if (isset($actions[$name])) {
                    throw new \InvalidArgumentException(sprintf('Action "%s" is already defined', $name));
                }

                return $name;
            });
            if (!$actionName) {
                break;
            }
            
            $usualActions = array('home', 'add', 'see', 'modify', 'delete');
            if(in_array($actionName, $usualActions)) {
                $route          = '/'.$actionName;
                $placeholders   = $this->getPlaceholdersFromRoute($route);
                $template       = $input->getOption('controller').':'.$actionName.'.html.'.$input->getOption('template-format');
            } 
            else {
                // route
                $route = $dialog->ask($output, $dialog->getQuestion('Action route', '/'.$actionName), '/'.$actionName);
                $placeholders = $this->getPlaceholdersFromRoute($route);

                // template
                $defaultTemplate = $input->getOption('controller').':'.$actionName.'.html.'.$input->getOption('template-format');
                $template = $dialog->askAndValidate($output, $dialog->getQuestion('Templatename (optional)', $defaultTemplate), $templateNameValidator, false, 'default');
            }
                        
            // adding action
            $actions[$actionName] = array(
                'name'         => $actionName,
                'route'        => $route,
                'placeholders' => $placeholders,
                'template'     => $template,
            );
        }

        return $actions;
    }

    public function parseActions($actions)
    {
        if (is_array($actions)) {
            return $actions;
        }

        $newActions = array();

        foreach (explode(' ', $actions) as $action) {
            $data = explode(':', $action);

            // name
            if (!isset($data[0])) {
                throw new \InvalidArgumentException('An action must have a name');
            }
            $name = array_shift($data);

            // route
            $route = (isset($data[0]) && '' != $data[0]) ? array_shift($data) : '/'.substr($name, 0, -6);
            if ($route) {
                $placeholders = $this->getPlaceholdersFromRoute($route);
            } else {
                $placeholders = array();
            }

            // template
            $template = (0 < count($data) && '' != $data[0]) ? implode(':', $data) : 'default';

            $newActions[$name] = array(
                'name'         => $name,
                'route'        => $route,
                'placeholders' => $placeholders,
                'template'     => $template,
            );
        }

        return $newActions;
    }

    public function getPlaceholdersFromRoute($route)
    {
        preg_match_all('/{(.*?)}/', $route, $placeholders);
        $placeholders = $placeholders[1];

        return $placeholders;
    }

    public function parseShortcutNotation($shortcut)
    {
        $entity = str_replace('/', '\\', $shortcut);

        if (false === $pos = strpos($entity, ':')) {
            throw new \InvalidArgumentException(sprintf('The controller name must contain a : ("%s" given, expecting something like AcmeBlogBundle:Post)', $entity));
        }

        return array(substr($entity, 0, $pos), substr($entity, $pos + 1));
    }

    protected function createGenerator()
    {
        return new \Oliorga\GeneratorBundle\Generator\ControllerGenerator($this->getContainer()->get('filesystem'));
    }
}
