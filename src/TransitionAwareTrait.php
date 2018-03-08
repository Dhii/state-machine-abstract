<?php

namespace Dhii\State;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;

/**
 * Common functionality for objects that are aware of a transition.
 *
 * @since [*next-version*]
 */
trait TransitionAwareTrait
{
    /**
     * The transition.
     *
     * @since [*next-version*]
     *
     * @var string|Stringable|null
     */
    protected $transition;

    /**
     * Retrieves the transition associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return string|Stringable|null The transition, if any.
     */
    protected function _getTransition()
    {
        return $this->transition;
    }

    /**
     * Sets the transition for this instance.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $transition The transition, or null.
     */
    protected function _setTransition($transition)
    {
        if ($transition !== null && !is_string($transition) && !($transition instanceof Stringable)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Argument is not a valid transition'),
                null,
                null,
                $transition
            );
        }

        $this->transition = $transition;
    }

    /**
     * Creates a new invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     * @param mixed|null             $argument The invalid argument, if any.
     *
     * @return InvalidArgumentException The new exception.
     */
    abstract protected function _createInvalidArgumentException(
        $message = null,
        $code = null,
        RootException $previous = null,
        $argument = null
    );

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see   sprintf()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);
}
