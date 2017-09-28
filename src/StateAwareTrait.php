<?php

namespace Dhii\State;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;

/**
 * Common functionality for objects that are aware of a state.
 *
 * @since [*next-version*]
 */
trait StateAwareTrait
{
    /**
     * The state.
     *
     * @since [*next-version*]
     *
     * @var string|Stringable|null
     */
    protected $state;

    /**
     * Retrieves the state associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return string|Stringable|null The state, if any.
     */
    protected function _getState()
    {
        return $this->state;
    }

    /**
     * Sets the state for this instance.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $state The state, or null.
     */
    protected function _setState($state)
    {
        if ($state !== null && !is_string($state) && !($state instanceof Stringable)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Argument is not a valid state or null.'),
                null,
                null,
                $state
            );
        }

        $this->state = $state;
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
