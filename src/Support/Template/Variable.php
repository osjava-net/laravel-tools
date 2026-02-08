<?php namespace QFrame\Support\Template;

use QFrame\Support\ValueObject;

/**
 * Class Variable
 * @package App\Support
 * @property string $expression
 * @property string $name
 * @property string $format
 * @property array $filters array(['type' => 'args'])
 */
class Variable extends ValueObject
{
    /**
     * Variable constructor.
     * @param string $expression
     */
    public function __construct(string $expression) {
        $this->expression = $expression;
    }
}
