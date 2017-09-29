<?php

namespace Dhii\State;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;
use Traversable;

/**
 * Common functionality for objects that are aware of a list of transitions.
 *
 * @since [*next-version*]
 */
trait TransitionListAwareTrait
{
    /**
     * The transitions associated with this instance.
     *
     * @since [*next-version*]
     *
     * @var string[]|Stringable[]
     */
    protected $transitions;

    /**
     * Retrieves the transitions associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return string[]|Stringable[]|Traversable The transitions.
     */
    protected function _getTransitions()
    {
        return $this->transitions === null
            ? $this->transitions = []
            : $this->transitions;
    }

    /**
     * Retrieves the key for a specific transition.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $transition The transition.
     *
     * @return string|Stringable The key of the given transition.
     */
    protected function _getTransitionKey($transition)
    {
        return (string) $transition;
    }

    /**
     * Retrieves the transition with a specific key.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $key The transition key.
     *
     * @return string|Stringable|null The transition, or null if no transition for the given key was found.
     */
    protected function _getTransition($key)
    {
        $sKey = (string) $key;

        return array_key_exists($sKey, $this->transitions)
            ? $this->transitions[$sKey]
            : null;
    }

    /**
     * Checks if the given transition exists for this instance.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $transition The transition.
     *
     * @return bool True if the transition exists for this instance, false if not.
     */
    protected function _hasTransition($transition)
    {
        return array_key_exists($this->_getTransitionKey($transition), $this->_getTransitions());
    }

    /**
     * Adds a transition to this instance.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $transition The transition to add.
     */
    protected function _addTransition($transition)
    {
        if (!is_string($transition) && !($transition instanceof Stringable)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Argument is not a valid transition.'),
                null,
                null,
                $transition
            );
        }

        $this->transitions[(string) $transition] = $transition;
    }

    /**
     * Adds multiple transitions to this instance.
     *
     * @since [*next-version*]
     *
     * @param string[]|Stringable[]|Traversable $transitions The transitions to add.
     */
    protected function _addTransitions($transitions)
    {
        foreach ($transitions as $_transition) {
            $this->_addTransition($_transition);
        }
    }

    /**
     * Sets multiple transitions to this instance, removing existing ones.
     *
     * @since [*next-version*]
     *
     * @param $transitions
     */
    protected function _setTransitions($transitions)
    {
        $this->_resetTransitions();
        $this->_addTransitions($transitions);
    }

    /**
     * Removes a transition from this instance.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $transition The transition to remove.
     */
    protected function _removeTransition($transition)
    {
        $key = $this->_getTransitionKey($transition);

        unset($this->transitions[$key]);
    }

    /**
     * Removes all transitions associated with this instance.
     *
     * @since [*next-version*]
     */
    protected function _resetTransitions()
    {
        $this->transitions = [];
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
