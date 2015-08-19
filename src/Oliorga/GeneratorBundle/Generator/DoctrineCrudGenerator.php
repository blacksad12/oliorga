<?php


namespace Oliorga\GeneratorBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class DoctrineCrudGenerator extends \Sensio\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator
{
    protected $routePrefix;
    protected $routeNamePrefix;
    protected $bundle;
    protected $entity;
    protected $metadata;
    protected $format;
    protected $actions;
    protected $bundleShort;
    protected $projectName;
    protected $namingArray;
    
    /**
     * Generate the CRUD controller.
     *
     * @param BundleInterface   $bundle           A bundle object
     * @param string            $entity           The entity relative class name
     * @param ClassMetadataInfo $metadata         The entity class metadata
     * @param string            $format           The configuration format (xml, yaml, annotation)
     * @param string            $routePrefix      The route name prefix
     * @param array             $needWriteActions Wether or not to generate write actions
     *
     * @throws \RuntimeException
     */
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata, $format, $routePrefix, $needWriteActions, $forceOverwrite)
    {
        $this->namingArray = Helper\NamingHelper::getNamingArray($bundle);
        $this->routePrefix = $routePrefix;
        $this->routeNamePrefix = $this->namingArray['vendor'].'_'.$this->namingArray['bundle'].'_'.strtolower($entity).'_';
        $this->actions = $needWriteActions ? array('home', 'add', 'modify', 'see', 'delete') : array('home', 'see');

        if (count($metadata->identifier) > 1) {
            throw new \RuntimeException('The CRUD generator does not support entity classes with multiple primary keys.');
        }

        if (!in_array('id', $metadata->identifier)) {
            throw new \RuntimeException('The CRUD generator expects the entity object has a primary key field named "id" with a getId() method.');
        }

        $this->entity   = $entity;
        $this->bundle   = $bundle;
        $this->metadata = $metadata;
        $this->format   = $format;

        $this->generateControllerClass($forceOverwrite);

        $dir = sprintf('%s/Resources/views/%s', $this->bundle->getPath(), str_replace('\\', '/', $this->entity));

        if (!file_exists($dir)) {
            $this->filesystem->mkdir($dir, 0777);
        }

        $this->generateHomeView($dir);
        $this->generateSeeView($dir);
        $this->generateAddView($dir);
        $this->generateModifyView($dir);
        $this->generateSeeEmbedView($dir);
        
        $this->generateTestClass();
        $this->generateMenuBuilderClass();
//        $this->generateConfiguration();
    }
    
    /**
     * Generates the routing configuration.
     *
     */
    protected function generateConfiguration()
    {
        if (!in_array($this->format, array('yml', 'xml', 'php'))) {
            return;
        }

        $target = sprintf(
            '%s/Resources/config/routing/%s.%s',
            $this->bundle->getPath(),
            strtolower(str_replace('\\', '_', $this->entity)),
            $this->format
        );

        $this->renderFile('crud/config/routing.'.$this->format.'.twig', $target, array(
            'actions'           => $this->actions,
            'route_prefix'      => $this->routePrefix,
            'route_name_prefix' => $this->routeNamePrefix,
            'bundle'            => $this->bundle->getName(),
            'entity'            => $this->entity,
        ));
    }

    /**
     * Generates the controller class only.
     *
     */
    protected function generateControllerClass($forceOverwrite)
    {
        $dir = $this->bundle->getPath();

        $parts = explode('\\', $this->entity);
        $entityClass = array_pop($parts);
        $entityNamespace = implode('\\', $parts);

        $target = sprintf(
            '%s/Controller/%s/%sController.php',
            $dir,
            str_replace('\\', '/', $entityNamespace),
            $entityClass
        );

        if (!$forceOverwrite && file_exists($target)) {
            throw new \RuntimeException('Unable to generate the controller as it already exists.');
        }

        $this->renderFile('crud/controller.php.twig', $target, array(
            'actions'           => $this->actions,
            'route_prefix'      => $this->routePrefix,
            'route_name_prefix' => $this->routeNamePrefix,
            'bundle'            => $this->bundle->getName(),
            'entity'            => $this->entity,
            'entity_class'      => $entityClass,
            'namespace'         => $this->bundle->getNamespace(),
            'entity_namespace'  => $entityNamespace,
            'format'            => $this->format,
        ));
    }

    /**
     * Generates the functional test class only.
     *
     */
    protected function generateTestClass()
    {
        $parts = explode('\\', $this->entity);
        $entityClass = array_pop($parts);
        $entityNamespace = implode('\\', $parts);

        $dir    = $this->bundle->getPath() .'/Tests/Controller';
        $target = $dir .'/'. str_replace('\\', '/', $entityNamespace).'/'. $entityClass .'ControllerTest.php';

        $this->renderFile('crud/tests/test.php.twig', $target, array(
            'entity'            => $this->entity,
            'bundle'            => $this->bundle->getName(),
            'bundleShort'       => $this->namingArray['Bundle'],              // Expl: Equipment
            'namespace'         => $this->bundle->getNamespace(),
            'entity_namespace'  => $entityNamespace,            
        ));
    }

    /**
     * Generates the KNP Menu Builder.
     */
    protected function generateMenuBuilderClass()
    {
        $parts = explode('\\', $this->entity);
        $entityClass = array_pop($parts);
        $entityNamespace = implode('\\', $parts);

        $dir    = $this->bundle->getPath() .'/Menu';
        $target = $dir .'/'. str_replace('\\', '/', $entityNamespace).'/Builder'. $entityClass .'.php';

        $this->renderFile('crud/menu/builder.php.twig', $target, array(
            'entity'            => $this->entity,
            'bundle'            => $this->bundle->getName(),
            'bundleShort'       => $this->namingArray['Bundle'],              // Expl: Equipment
            'namespace'         => $this->bundle->getNamespace(),
            'route_name_prefix' => $this->routeNamePrefix,          // Expl: webobs_equipment_model_
        ));
    }

    /**
     * Generates the home.html.twig template in the final bundle.
     *
     * @param string $dir The path to the folder that hosts templates in the bundle
     */
    protected function generateHomeView($dir)
    {
        $this->renderFile('crud/views/home.html.twig.twig', $dir.'/home.html.twig', array(
            'projectName'       => $this->projectName,              // Expl: Webobs
            'bundle'            => $this->bundle->getName(),        // Expl: WebobsEquipmentBundle
            'bundleShort'       => $this->namingArray['Bundle'],              // Expl: Equipment
            'entity'            => $this->entity,                   // Expl: Model
            'fields'            => $this->getFieldsFromMetadata($this->metadata),  // key=>value array of non-associative ('simple') and associative ('association') fields
            'route_name_prefix' => $this->routeNamePrefix,          // Expl: webobs_equipment_model_
        ));
    }

    /**
     * Generates the see.html.twig template in the final bundle.
     *
     * @param string $dir The path to the folder that hosts templates in the bundle
     */
    protected function generateSeeView($dir)
    {
        $this->renderFile('crud/views/see.html.twig.twig', $dir.'/see.html.twig', array(
            'projectName'       => $this->projectName,              // Expl: Webobs
            'bundle'            => $this->bundle->getName(),        // Expl: WebobsEquipmentBundle
            'bundleShort'       => $this->namingArray['Bundle'],              // Expl: Equipment
            'entity'            => $this->entity,
            'fields'            => $this->getFieldsFromMetadata($this->metadata),  // key=>value array of non-associative ('simple') and associative ('association') fields
        ));
    }

    /**
     * Generates the seeEmbed.html.twig template in the final bundle.
     *
     * @param string $dir The path to the folder that hosts templates in the bundle
     */
    protected function generateSeeEmbedView($dir)
    {
        $this->renderFile('crud/views/seeEmbed.html.twig.twig', $dir.'/seeEmbed.html.twig', array(
            'projectName'       => $this->projectName,              // Expl: Webobs
            'bundle'            => $this->bundle->getName(),        // Expl: WebobsEquipmentBundle
            'bundleShort'       => $this->namingArray['Bundle'],              // Expl: Equipment
            'entity'            => $this->entity,
            'fields'            => $this->getFieldsFromMetadata($this->metadata),  // key=>value array of non-associative ('simple') and associative ('association') fields
            'route_name_prefix' => $this->routeNamePrefix,          // Expl: webobs_equipment_model_
        ));
    }

    /**
     * Generates the add.html.twig template in the final bundle.
     *
     * @param string $dir The path to the folder that hosts templates in the bundle
     */
    protected function generateAddView($dir)
    {
        $this->renderFile('crud/views/add.html.twig.twig', $dir.'/add.html.twig', array(
            'projectName'       => $this->projectName,              // Expl: Webobs
            'bundle'            => $this->bundle->getName(),        // Expl: WebobsEquipmentBundle
            'bundleShort'       => $this->namingArray['Bundle'],              // Expl: Equipment
            'entity'            => $this->entity,
        ));
    }

    /**
     * Generates the modify.html.twig template in the final bundle.
     *
     * @param string $dir The path to the folder that hosts templates in the bundle
     */
    protected function generateModifyView($dir)
    {
        $this->renderFile('crud/views/modify.html.twig.twig', $dir.'/modify.html.twig', array(
            'projectName'       => $this->projectName,              // Expl: Webobs
            'bundle'            => $this->bundle->getName(),        // Expl: WebobsEquipmentBundle
            'bundleShort'       => $this->namingArray['Bundle'],              // Expl: Equipment
            'route_name_prefix' => $this->routeNamePrefix,
            'entity'            => $this->entity,
        ));
    }
    
    /**
     * Returns an array of fields. Fields can be both column fields and
     * association fields.
     *
     * @param  ClassMetadataInfo $metadata
     * @return array             $fields
     */
    private function getFieldsFromMetadata(ClassMetadataInfo $metadata)
    {
        $fields = array(
            'simple'        => array(),
            'association'   => array(),
        );
        foreach ($metadata->fieldMappings as $fieldName => $fieldMetadata) {
            if (!in_array($fieldName, $metadata->identifier)) { // Remove the primary key field
                $fields['simple'][$fieldName] = $fieldMetadata;
            }
        }
        
        foreach ($metadata->associationMappings as $fieldName => $relation) {
            if ($relation['type'] !== ClassMetadataInfo::ONE_TO_MANY) {
                $fields['association'][$fieldName] = $relation;
                $fields['association'][$fieldName]['route_name_prefix'] = $this->getRoutePrefixFromEntityPath($relation['targetEntity']);
            }
        }
        return $fields;
    }
    
    /**
     * Get RoutePrefix
     * FROM : Oliorga\CoreBundle\Chain
     * TO   : webobs_core_chain_
     * @param string $entityPath
     * @return string
     */
    private function getRoutePrefixFromEntityPath($entityPath) {
        $pathArray = split('\\\\', $entityPath);
        return strtolower($pathArray[0]).'_'.strtolower(str_replace('Bundle', '', $pathArray[1])).'_'.strtolower($pathArray[3]).'_';
    }
}
