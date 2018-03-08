<?php

namespace Dhii\State\UnitTest;

use Dhii\State\StateMachineInterface;
use Exception;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionMethod;
use Xpmock\TestCase;

/**
 * Tests {@see AbstractStateMachine}.
 *
 * @since [*next-version*]
 */
class AbstractStateMachineTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\State\AbstractStateMachine';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createInstance()
    {
        $mock = $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);

        return $mock;
    }

    /**
     * Creates a mock state machine for testing purposes.
     *
     * @since [*next-version*]
     *
     * @return StateMachineInterface
     */
    public function createStateMachine()
    {
        $mock = $this->mock('Dhii\State\StateMachineInterface')
                     ->transition()
                     ->canTransition();

        return $mock->new();
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests the transition method when the {@link AbstractStateMachine::_canTransition()} method returns true.
     *
     * @since [*next-version*]
     */
    public function testTransitionSuccess()
    {
        $subject = $this->createInstance();

        $transition = uniqid('transition-');
        $subject->expects($this->once())
                ->method('_canTransition')
                ->with($transition)
                ->willReturn(true);

        $stateMachine = $this->createStateMachine();
        $subject->expects($this->once())
                ->method('_applyTransition')
                ->with($transition)
                ->willReturn($stateMachine);

        $method = new ReflectionMethod(static::TEST_SUBJECT_CLASSNAME, '_transition');
        $method->setAccessible(true);

        $result = $method->invokeArgs($subject, [$transition]);

        $this->assertSame($stateMachine, $result, 'Returned state machine is not expected result instance.');
    }

    /**
     * Tests the transition method when the {@link AbstractStateMachine::_canTransition()} method returns false.
     *
     * @since [*next-version*]
     */
    public function testTransitionFailure()
    {
        $this->setExpectedException('Exception');

        $subject = $this->createInstance();

        $transition = uniqid('transition-');
        $subject->expects($this->once())
                ->method('_canTransition')
                ->with($transition)
                ->willReturn(false);

        $subject->expects($this->once())
                ->method('_createCouldNotTransitionException')
                ->with($this->anything(), $this->anything(), $this->anything(), $transition)
                ->willReturn(new Exception());

        $method = new ReflectionMethod(static::TEST_SUBJECT_CLASSNAME, '_transition');
        $method->setAccessible(true);

        $method->invokeArgs($subject, [$transition]);
    }
}
