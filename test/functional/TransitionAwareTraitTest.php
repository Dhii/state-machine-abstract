<?php

namespace Dhii\State\FuncTest;

use Dhii\State\TransitionAwareTrait as TestSubject;
use InvalidArgumentException;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class TransitionAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\State\TransitionAwareTrait';

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
     * Tests the transition getter and setter methods with a string transition.
     *
     * @since [*next-version*]
     */
    public function testGetSetTransition()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setTransition($transition = uniqid('transition-'));

        $this->assertSame($transition, $reflect->_getTransition(), 'Set and retrieved transition are not the same.');
    }

    /**
     * Tests the transition getter and setter methods with a null value.
     *
     * @since [*next-version*]
     */
    public function testGetSetTransitionNull()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setTransition(null);

        $this->assertNull($reflect->_getTransition(), 'Retrieved transition is not null');
    }

    /**
     * Tests the transition getter and setter methods with a stringable transition object.
     *
     * @since [*next-version*]
     */
    public function testGetSetTransitionStringable()
    {
        $stringable = $this->mock('Dhii\Util\String\StringableInterface')
                           ->__toString($transition = uniqid('transition-'))
                           ->new();

        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setTransition($stringable);

        $this->assertSame($stringable, $reflect->_getTransition(), 'Set and retrieved transition are not the same.');
        $this->assertSame(
            $transition,
            (string) $reflect->_getTransition(),
            'Retrieved stringable transition is invalid.'
        );
    }

    /**
     * Tests the transition getter and setter methods with an invalid transition.
     *
     * @since [*next-version*]
     */
    public function testGetSetTransitionInvalid()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setTransition(new stdClass());
    }
}
