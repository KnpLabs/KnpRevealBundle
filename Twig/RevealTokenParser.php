<?php

namespace Knp\RevealBundle\Twig;

use Twig_Token;
use Twig_TokenParser;

class RevealTokenParser extends Twig_TokenParser
{
    public function parse(Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $name = null;
        if ($stream->test(Twig_Token::NAME_TYPE)) {
            $name = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
        }
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $content = $this->parser->subparse(array($this, 'decideCodeEnd'), true);
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new RevealCodeNode($content, $name, $lineno, $this->getTag());
    }

    public function decideCodeEnd(Twig_Token $token)
    {
        return $token->test('endcode');
    }

    public function getTag()
    {
        return 'code';
    }
}