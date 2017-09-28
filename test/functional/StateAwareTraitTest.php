<?php

namespace Dhii\State\FuncTest;

use Dhii\State\StateAwareTrait as TestSubject;
use InvalidArgumentException;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class StateAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\State\StateAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return TestSubject
     */
    public function createInstance()
    {
        // Create mock
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME);

        $mock->method('_createInvalidArgumentException')->willReturnCallback(
            function() {
                return new InvalidArgumentException();
            }
        );

        $mock->method('__')->willReturnArgument(0);

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType(
            'object',
            $subject,
            'An instance of the test subject could not be created'
        );
    }

    /**
     * Tests the state getter and setter methods with a state string.
     *
     * @since [*next-version*]
     */
    public function testGetSetState()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setState($state = uniqid('state-'));

        $this->assertSame($state, $reflect->_getState(), 'Set and retrieved states are not the same.');
    }

    /**
     * Tests the state getter and setter methods with a null value.
     *
     * @since [*next-version*]
     */
    public function testGetSetStateNull()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setState(null);

        $this->assertNull($reflect->_getState(), 'Retrieved state is not null');
    }

    /**
     * Tests the state getter and setter methods with a stringable state object.
     *
     * @since [*next-version*]
     */
    public function testGetSetStateStringable()
    {
        $stringable = $this->mock('Dhii\Util\String\StringableInterface')
                           ->__toString($state = uniqid('state-'))
                           ->new();

        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setState($stringable);

        $this->assertSame($stringable, $reflect->_getState(), 'Set and retrieved states are not the same.');
        $this->assertSame($state, (string) $reflect->_getState(), 'Retrieved stringable state is invalid.');
    }

    /**
     * Tests the state getter and setter methods with an invalid state.
     *
     * @since [*next-version*]
     */
    public function testGetSetStateInvalid()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setState(new stdClass());
    }
}
