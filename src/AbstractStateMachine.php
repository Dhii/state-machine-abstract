<?php

namespace Dhii\State;

use Dhii\State\Exception\CouldNotTransitionExceptionInterface;
use Dhii\State\Exception\StateMachineExceptionInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception;

/**
 * Abstract common functionality for state machines.
 *
 * @since [*next-version*]
 */
abstract class AbstractStateMachine
{
    /**
     * Applies a transition.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $transition The transition code.
     *
     * @throws CouldNotTransitionExceptionInterface If the transition failed or was aborted.
     * @throws StateMachineExceptionInterface       If an error was encountered during transition.
     *
     * @return StateMachineInterface The state machine with the new state.
     */
    protected function _transition($transition)
    {
        // todo
    }

    /**
     * Applies a transition to this state machine.
     *
     * The transition given to this method can be considered pre-validated.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $transition The transition to apply.
     *
     * @throws CouldNotTransitionExceptionInterface If the transition failed or was aborted.
     * @throws StateMachineExceptionInterface       If an error was encountered during transition.
     *
     * @return StateMachineInterface The state machine with the new state.
     */
    abstract protected function _applyTransition($transition);

    /**
     * Checks if a transition can be applied.
     *
     * This method does not guarantee that the transition will be successful.
     * It only indicates that a given transition can, at the very least, be attempted.
     * The transition, when applied, is still permitted to fail and throw an exception,
     * even if this method returns `true`.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $transition The transition to check.
     *
     * @return bool True if the transition is possible, false if not.
     */
    abstract protected function _canTransition($transition);

    /**
     * Creates a state machine exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param Exception|null         $previous The inner exception for chaining, if any.
     *
     * @return StateMachineExceptionInterface The created state machine exception.
     */
    abstract protected function _createStateMachineException($message = null, $code = null, Exception $previous = null);

    /**
     * Creates an exception for transition failure.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message    The error message, if any.
     * @param int|null               $code       The error code, if any.
     * @param Exception|null         $previous   The inner exception for chaining, if any.
     * @param string|Stringable|null $transition The transition that failed, if any.
     *
     * @return StateMachineExceptionInterface The created exception.
     */
    abstract protected function _createCouldNotTransitionException(
        $message = null,
        $code = null,
        Exception $previous = null,
        $transition = null
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
