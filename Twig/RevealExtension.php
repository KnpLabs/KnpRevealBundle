<?php

namespace Knp\RevealBundle\Twig;

use Twig_Extension;

class RevealExtension extends Twig_Extension
{
    public function getTokenParsers()
    {
        return array(new RevealTokenParser());
    }

    public function getName()
    {
        return 'reveal_extension';
    }
}