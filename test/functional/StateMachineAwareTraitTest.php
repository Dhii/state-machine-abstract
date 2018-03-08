<?php

namespace Dhii\State\FuncTest;

use InvalidArgumentException;
use stdClass;
use Xpmock\TestCase;
use Dhii\State\StateMachineAwareTrait as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class StateMachineAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\State\StateMachineAwareTrait';

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
            function () {
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
     * Tests the state machine getter and setter methods.
     *
     * @since [*next-version*]
     */
    public function testGetSetStateMachine()
    {
        $stateMachine = $this->mock('Dhii\State\StateMachineInterface')
                             ->transition()
                             ->canTransition()
                             ->new();

        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setStateMachine($stateMachine);

        $this->assertSame(
            $stateMachine,
            $reflect->_getStateMachine(),
            'Set and retrieved state machines are not the same.'
        );
    }

    /**
     * Tests the state machine getter and setter methods with a null value.
     *
     * @since [*next-version*]
     */
    public function testGetSetStateMachineNull()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setStateMachine(null);

        $this->assertNull($reflect->_getStateMachine(), 'Retrieved state machines is not null.');
    }

    /**
     * Tests the state machine getter and setter methods.
     *
     * @since [*next-version*]
     */
    public function testGetSetStateMachineInvalid()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setStateMachine(new stdClass());
    }
}
