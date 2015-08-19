<?php

namespace Oliorga\GeneratorBundle\Generator\Helper;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class NamingHelper
{
    public static function getNamingArray(BundleInterface $bundle) {
        $namingArray = array();
        
        $namespaceExploded = explode('\\', $bundle->getNamespace());
        $namingArray['Bundle'] = str_replace('Bundle', '', $namespaceExploded[1]);
        $namingArray['bundle'] = strtolower($namingArray['Bundle']);
        $namingArray['Vendor'] = $namespaceExploded[0];
        $namingArray['vendor'] = strtolower($namingArray['Vendor']);
        
        return $namingArray;
    }
}
