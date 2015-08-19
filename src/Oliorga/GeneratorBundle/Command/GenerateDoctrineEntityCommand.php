<?php

namespace Oliorga\GeneratorBundle\Command;

use Oliorga\GeneratorBundle\Generator\DoctrineEntityGenerator;
use Oliorga\GeneratorBundle\Command\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Doctrine\DBAL\Types\Type;

/**
 * Initializes a Doctrine entity inside a bundle.
 */
class GenerateDoctrineEntityCommand extends \Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineEntityCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setAliases(array('oliorga:generate:entity'));
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();
        $questionHelper->writeSection($output, 'Welcome to the Doctrine2 entity generator');

        // namespace
        $output->writeln(array(
            '',
            'This command helps you generate Doctrine2 entities.',
            '',
            'First, you need to give the entity name you want to generate.',
            'You must use the shortcut notation like <comment>AcmeBlogBundle:Post</comment>.',
            ''
        ));

        $bundleNames = array_keys($this->getContainer()->get('kernel')->getBundles());

        while (true) {
            $question = new Question($questionHelper->getQuestion('The Entity shortcut name', $input->getOption('entity')), $input->getOption('entity'));
            $question->setValidator(array('Oliorga\GeneratorBundle\Command\Validators', 'validateEntityName'));
            $question->setAutocompleterValues($bundleNames);
            $entity = $questionHelper->ask($input, $output, $question);

            list($bundle, $entity) = $this->parseShortcutNotation($entity);

            // check reserved words
            if ($this->getGenerator()->isReservedKeyword($entity)){
                $output->writeln(sprintf('<bg=red> "%s" is a reserved word</>.', $entity));
                continue;
            }

            try {
                $b = $this->getContainer()->get('kernel')->getBundle($bundle);

                if (!file_exists($b->getPath().'/Entity/'.str_replace('\\', '/', $entity).'.php')) {
                    break;
                }

                $output->writeln(sprintf('<bg=red>Entity "%s:%s" already exists</>.', $bundle, $entity));
            } catch (\Exception $e) {
                $output->writeln(sprintf('<bg=red>Bundle "%s" does not exist.</>', $bundle));
            }
        }
        $input->setOption('entity', $bundle.':'.$entity);

        // format
        $format = 'annotation';
        $input->setOption('format', $format);

        // fields
        $input->setOption('fields', $this->addFields($input, $output, $questionHelper));

        // repository?
        $input->setOption('with-repository', 'yes');

        // summary
        $output->writeln(array(
            '',
            $this->getHelper('formatter')->formatBlock('Summary before generation', 'bg=blue;fg=white', true),
            '',
            sprintf("You are going to generate a \"<info>%s:%s</info>\" Doctrine2 entity", $bundle, $entity),
            sprintf("using the \"<info>%s</info>\" format.", $format),
            '',
        ));
    }
    
    private function parseFields($input)
    {
        if (is_array($input)) {
            return $input;
        }

        $fields = array();
        foreach (explode(' ', $input) as $value) {
            $elements = explode(':', $value);
            $name = $elements[0];
            if (strlen($name)) {
                $type = isset($elements[1]) ? $elements[1] : 'string';
                preg_match_all('/(.*)\((.*)\)/', $type, $matches);
                $type = isset($matches[1][0]) ? $matches[1][0] : $type;
                $length = isset($matches[2][0]) ? $matches[2][0] : null;

                $fields[$name] = array('fieldName' => $name, 'type' => $type, 'length' => $length);
            }
        }

        return $fields;
    }

    protected function addFields(InputInterface $input, OutputInterface $output, \Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper $questionHelper)
    {
        $fields = $this->parseFields($input->getOption('fields'));
        $output->writeln(array(
            '',
            'Instead of starting with a blank entity, you can add some fields now.',
            'Note that the primary key will be added automatically (named <comment>id</comment>).',
            '',
        ));
        $output->write('<info>Available types:</info> ');

        $types = array_keys(Type::getTypesMap());
        $count = 20;
        foreach ($types as $i => $type) {
            if ($count > 50) {
                $count = 0;
                $output->writeln('');
            }
            $count += strlen($type);
            $output->write(sprintf('<comment>%s</comment>', $type));
            if (count($types) != $i + 1) {
                $output->write(', ');
            } else {
                $output->write('.');
            }
        }
        $output->writeln('');

        $fieldValidator = function ($type) use ($types) {
            // FIXME: take into account user-defined field types
            if (!in_array($type, $types)) {
                throw new \InvalidArgumentException(sprintf('Invalid type "%s".', $type));
            }

            return $type;
        };

        $lengthValidator = function ($length) {
            if (!$length) {
                return $length;
            }

            $result = filter_var($length, FILTER_VALIDATE_INT, array(
                'options' => array('min_range' => 1)
            ));

            if (false === $result) {
                throw new \InvalidArgumentException(sprintf('Invalid length "%s".', $length));
            }

            return $length;
        };

        while (true) {
            $output->writeln('');
            $generator = $this->getGenerator();
            $question = new Question($questionHelper->getQuestion('New field name (press <return> to stop adding fields)', null), null);
            $question->setValidator(function ($name) use ($fields, $generator) {
                if (isset($fields[$name]) || 'id' == $name) {
                    throw new \InvalidArgumentException(sprintf('Field "%s" is already defined.', $name));
                }

                // check reserved words
                if ($generator->isReservedKeyword($name)) {
                    throw new \InvalidArgumentException(sprintf('Name "%s" is a reserved word.', $name));
                }

                return $name;
            });

            $columnName = $questionHelper->ask($input, $output, $question);            
            if (!$columnName) {
                break;
            }

            $defaultType = 'string';

            // try to guess the type by the column name prefix/suffix
            if (substr($columnName, -3) == '_at') {
                $defaultType = 'datetime';
            } elseif (substr($columnName, -3) == '_id') {
                $defaultType = 'integer';
            } elseif (substr($columnName, 0, 3) == 'is_') {
                $defaultType = 'boolean';
            } elseif (substr($columnName, 0, 4) == 'has_') {
                $defaultType = 'boolean';
            }

            $question = new Question($questionHelper->getQuestion('Field type', $defaultType), $defaultType);
            $question->setValidator($fieldValidator);
            $question->setAutocompleterValues($types);
            $type = $questionHelper->ask($input, $output, $question);
            
            // isNullable
            $defaultIsNullable = 'yes';
            $possibleIsNullable = ['yes', 'no'];
            $isNullableValidator = function ($isNullable) use ($possibleIsNullable) {
                if (!in_array($isNullable, $possibleIsNullable)) {
                    throw new \InvalidArgumentException(sprintf('Invalid isNullable "%s".', $isNullable));
                }
                return $isNullable;
            };
            $question = new Question($questionHelper->getQuestion('Nullable?', $defaultIsNullable), $defaultIsNullable);
            $question->setValidator($isNullableValidator);
            $question->setAutocompleterValues($possibleIsNullable);
            $isNullable = $questionHelper->ask($input, $output, $question);
            $isNullable = $isNullable === 'yes';
            
            $data = array('columnName' => $columnName, 'fieldName' => lcfirst(Container::camelize($columnName)), 'type' => $type, 'nullable' => $isNullable);

            if ($type == 'string') {
                $question = new Question($questionHelper->getQuestion('Field length', 255), 255);
                $question->setValidator($lengthValidator);
                $data['length'] = $questionHelper->ask($input, $output, $question);
            }
            
            $fields[$columnName] = $data;
        }

        return $fields;
    }
    
    protected function createGenerator()
    {
        return new \Oliorga\GeneratorBundle\Generator\DoctrineEntityGenerator($this->getContainer()->get('filesystem'), $this->getContainer()->get('doctrine'));
    }
}
