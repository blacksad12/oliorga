<?php

namespace Oliorga\GeneratorBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class ControllerGenerator extends \Sensio\Bundle\GeneratorBundle\Generator\ControllerGenerator
{
    
    public function generate(BundleInterface $bundle, $controller, $routeFormat, $templateFormat, array $actions = array())
    {
        $dir = $bundle->getPath();
        $controllerFile = $dir.'/Controller/'.$controller.'Controller.php';
        if (file_exists($controllerFile)) {
            throw new \RuntimeException(sprintf('Controller "%s" already exists', $controller));
        }

        // seeRoute
        $bundleShortName = substr($bundle->getName(), strlen('Webobs'), strlen($bundle->getName())-(strlen('Bundle')+strlen('Webobs')));
        $seeRoute = 'webobs_'.strtolower($bundleShortName).'_'.strtolower($controller).'_see';
        $parameters = array(
            'namespace'  => $bundle->getNamespace(),
            'bundle'     => $bundle->getName(),
            'format'     => array(
                'routing'    => $routeFormat,
                'templating' => $templateFormat,
            ),
            'entity'    => $controller,
            'seeRoute'  => $seeRoute
        );
        
        foreach ($actions as $i => $action) {
            // get the actioname without the sufix Action (for the template logical name)
            $actions[$i]['basename'] = $action['name'];
            $params = $parameters;
            $params['action'] = $actions[$i];

            // create a template
            $template = $actions[$i]['template'];
            if ('default' == $template) {
                $template = $bundle->getName().':'.$controller.':'.$action['name'].'.html.'.$templateFormat;
            }

            $this->generateRouting($bundle, $controller, $actions[$i], $routeFormat);
        }

        $parameters['actions'] = $actions;

        $this->renderFile('controller/Controller.php.twig', $controllerFile, $parameters);
        $this->renderFile('controller/ControllerTest.php.twig', $dir.'/Tests/Controller/'.$controller.'ControllerTest.php', $parameters);
    }
}
