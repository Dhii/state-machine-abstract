<?php

namespace Dhii\State;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;

/**
 * Common functionality for objects that are aware of state machine.
 *
 * @since [*next-version*]
 */
trait StateMachineAwareTrait
{
    /**
     * The state machine instance.
     *
     * @since [*next-version*]
     *
     * @var StateMachineInterface|null
     */
    protected $stateMachine;

    /**
     * Retrieves the state machine associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return StateMachineInterface|null The state machine instance, if any.
     */
    protected function _getStateMachine()
    {
        return $this->stateMachine;
    }

    /**
     * Sets the state machine for this instance.
     *
     * @since [*next-version*]
     *
     * @param StateMachineInterface|null $stateMachine The state machine instance or null
     */
    protected function _setStateMachine($stateMachine)
    {
        if ($stateMachine !== null && !($stateMachine instanceof StateMachineInterface)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Argument is not a valid state machine instance or null.'),
                null,
                null,
                $stateMachine
            );
        }

        $this->stateMachine = $stateMachine;
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
