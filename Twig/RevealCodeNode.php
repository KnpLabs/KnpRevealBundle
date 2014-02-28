<?php

namespace Knp\RevealBundle\Twig;

use Twig_Compiler;
use Twig_Node;
use Twig_Node_Text;
use Twig_Template;

class RevealCodeNode extends Twig_Node
{
    public function __construct(Twig_Node_Text $content = null, $name = null, $lineno, $tag = null)
    {
        parent::__construct(array('content' => $content), array('name' => $name), $lineno, $tag);
    }

    public function compile(Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write(sprintf(
                "echo '<pre><code data-trim class=\"%s\">%s</code></pre>';\n",
                $this->getAttribute('name'),
                addslashes(htmlentities($this->getNode('content')->getAttribute('data')))
            ))
        ;
    }
}