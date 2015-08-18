<?php

namespace Oliorga\GeneratorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * OliorgaGeneratorBundle.
 */
class OliorgaGeneratorBundle extends Bundle
{
    public function getParent()
    {
        return 'SensioGeneratorBundle';
    }
}
