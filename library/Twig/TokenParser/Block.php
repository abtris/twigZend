<?php

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2009 Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Twig_TokenParser_Block extends Twig_TokenParser
{
  public function parse(Twig_Token $token)
  {
    $lineno = $token->getLine();
    $name = $this->parser->getStream()->expect(Twig_Token::NAME_TYPE)->getValue();
    if ($this->parser->hasBlock($name))
    {
      throw new Twig_SyntaxError("The block '$name' has already been defined", $lineno);
    }
    $this->parser->setCurrentBlock($name);
    $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);
    $body = $this->parser->subparse(array($this, 'decideBlockEnd'), true);
    if ($this->parser->getStream()->test(Twig_Token::NAME_TYPE))
    {
      $value = $this->parser->getStream()->expect(Twig_Token::NAME_TYPE)->getValue();

      if ($value != $name)
      {
        throw new Twig_SyntaxError(sprintf("Expected endblock for block '$name' (but %s given)", $value), $lineno);
      }
    }
    $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

    $block = new Twig_Node_Block($name, $body, $lineno);
    $this->parser->setBlock($name, $block);
    $this->parser->setCurrentBlock(null);

    return new Twig_Node_BlockReference($name, $lineno, $this->getTag());
  }

  public function decideBlockEnd($token)
  {
    return $token->test('endblock');
  }

  public function getTag()
  {
    return 'block';
  }
}
