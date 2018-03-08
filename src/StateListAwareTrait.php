<?php

namespace Dhii\State;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;
use Traversable;

/**
 * Common functionality for objects that are aware of a list of states.
 *
 * @since [*next-version*]
 */
trait StateListAwareTrait
{
    /**
     * The states associated with this instance.
     *
     * @since [*next-version*]
     *
     * @var string[]|Stringable[]
     */
    protected $states;

    /**
     * Retrieves the states associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return string[]|Stringable[]|Traversable The states.
     */
    protected function _getStates()
    {
        return $this->states === null
            ? $this->states = []
            : $this->states;
    }

    /**
     * Retrieves the key for a specific state.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $state The state.
     *
     * @return string|Stringable The key of the given state.
     */
    protected function _getStateKey($state)
    {
        return (string) $state;
    }

    /**
     * Retrieves the state with a specific key.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $key The state key.
     *
     * @return string|Stringable|null The state, or null if no state for the given key was found.
     */
    protected function _getState($key)
    {
        $sKey = (string) $key;

        return array_key_exists($sKey, $this->states)
            ? $this->states[$sKey]
            : null;
    }

    /**
     * Checks if the given state exists for this instance.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $state The state.
     *
     * @return bool True if the state exists for this instance, false if not.
     */
    protected function _hasState($state)
    {
        return array_key_exists($this->_getStateKey($state), $this->_getStates());
    }

    /**
     * Adds a state to this instance.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $state The state to add.
     */
    protected function _addState($state)
    {
        if (!is_string($state) && !($state instanceof Stringable)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Argument is not a valid state.'),
                null,
                null,
                $state
            );
        }

        $this->states[(string) $state] = $state;
    }

    /**
     * Adds multiple states to this instance.
     *
     * @since [*next-version*]
     *
     * @param string[]|Stringable[]|Traversable $states The states to add.
     */
    protected function _addStates($states)
    {
        foreach ($states as $_state) {
            $this->_addState($_state);
        }
    }

    /**
     * Sets multiple states to this instance, removing existing ones.
     *
     * @since [*next-version*]
     *
     * @param string[]|Stringable[]|Traversable $states The states to set.
     */
    protected function _setStates($states)
    {
        $this->_resetStates();
        $this->_addStates($states);
    }

    /**
     * Removes a state from this instance.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $state The state to remove.
     */
    protected function _removeState($state)
    {
        $key = $this->_getStateKey($state);

        unset($this->states[$key]);
    }

    /**
     * Removes all states associated with this instance.
     *
     * @since [*next-version*]
     */
    protected function _resetStates()
    {
        $this->states = [];
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
