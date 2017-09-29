<?php

namespace Dhii\State\FuncTest;

use Dhii\Util\String\StringableInterface;
use Exception;
use PHPUnit_Framework_MockObject_MockObject;
use Xpmock\TestCase;

/**
 * Tests {@see Dhii\State\TransitionListAwareTrait}.
 *
 * @since [*next-version*]
 */
class TransitionListAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\State\TransitionListAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createInstance(array $methods = [])
    {
        // Create mock
        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods($methods)
                     ->getMockForTrait();

        return $mock;
    }

    /**
     * Creates a mock stringable object for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param string|StringableInterface $string The string to return when the mocked object is casted to a string.
     *
     * @return StringableInterface The mocked stringable object.
     */
    public function createStringable($string)
    {
        $mock = $this->mock('Dhii\Util\String\StringableInterface')
                     ->__toString($string);

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

        $this->assertInternalType(
            'object',
            $subject,
            'An instance of the test subject could not be created'
        );
    }

    /**
     * Tests the transition key getter method.
     *
     * @since [*next-version*]
     */
    public function testGetTransitionKey()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $transition = uniqid('transition-');

        $this->assertEquals(
            $transition,
            $reflect->_getTransitionKey($transition),
            'Retrieved transition key and given transition string are not the same.'
        );
    }

    /**
     * Tests the transition key getter method with a stringable transition instance.
     *
     * @since [*next-version*]
     */
    public function testGetTransitionKeyStringable()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $transition = $this->createStringable($key = uniqid('transition-'));

        $this->assertEquals(
            $key,
            $reflect->_getTransitionKey($transition),
            'Retrieved transition key and key of given transition instance are not the same.'
        );
    }

    /**
     * Tests the transition adder and getter methods to ensure that correct transition adding and retrieval.
     *
     * @since [*next-version*]
     */
    public function testAddGetTransition()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);
        $transition = uniqid('transition-');

        $reflect->_addTransition($transition);

        $this->assertContains(
            $transition,
            $reflect->_getTransitions(),
            'Retrieved transition list does not have transition.'
        );
        $this->assertArrayHasKey(
            $transition,
            $reflect->_getTransitions(),
            'Retrieved transition list does not have transition key.'
        );
        $this->assertEquals(
            $transition,
            $reflect->_getTransition($transition),
            'Retrieved and added transitions are not the same.'
        );
    }

    /**
     * Tests the transition adder and getter methods with a stringable transition instance.
     *
     * @since [*next-version*]
     */
    public function testAddGetTransitionStringable()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);
        $key = uniqid('transition-');
        $transition = $this->createStringable($key);

        $reflect->_addTransition($transition);

        $list = $reflect->_getTransitions();

        $this->assertArrayHasKey($key, $list, 'Retrieved transition list does not have transition key.');
        $this->assertSame($transition, $list[$key], 'Transition in list is not same as given stringable instance.');
        $this->assertEquals(
            $transition,
            $reflect->_getTransition($key),
            'Transition retrieved by key and added transition are not the same.'
        );
        $this->assertEquals(
            $transition,
            $reflect->_getTransition($transition),
            'Transition retrieved by instance and added transition are not the same.'
        );
    }

    /**
     * Tests the multiple transition adder method and the getter method to ensure that all transitions are correctly
     * added and retrieved.
     *
     * @since [*next-version*]
     */
    public function testAddGetTransitions()
    {
        $subject = $this->createInstance(['_addTransition']);

        $reflect = $this->reflect($subject);
        $transitions = [
            uniqid('transition-'),
            uniqid('transition-'),
            $this->createStringable(uniqid('transition-')),
        ];

        $subject->expects($this->exactly(3))
                ->method('_addTransition')
                ->withConsecutive([$transitions[0]], [$transitions[1]], [$transitions[2]]);

        $reflect->_addTransitions($transitions);
    }

    /**
     * Tests the transition adder and checker methods to ensure that correct transition adding and existence checking.
     *
     * @since [*next-version*]
     */
    public function testAddHasTransition()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $transition = uniqid('transition-');

        $reflect->_addTransition($transition);

        $this->assertTrue($reflect->_hasTransition($transition), 'Transition was added, should have returned true.');
    }

    /**
     * Tests the transition adder and checker methods with a stringable transition instance.
     *
     * @since [*next-version*]
     */
    public function testAddHasTransitionStringable()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $key = uniqid('transition-');
        $transition = $this->createStringable($key);

        $reflect->_addTransition($transition);

        $this->assertTrue($reflect->_hasTransition($key), 'Transition with key was added, should have returned true');
        $this->assertTrue($reflect->_hasTransition($transition), 'Transition was added, should have returned true.');
    }

    /**
     * Tests the transition adder, removal and checker methods to ensure that added transitions are correctly removed.
     *
     * @since [*next-version*]
     */
    public function testAddRemoveHasTransition()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $transitions = [
            $key1 = uniqid('transition-'),
            $this->createStringable($key2 = uniqid('transition-')),
            $this->createStringable($key3 = uniqid('transition-')),
        ];

        $reflect->_addTransitions($transitions);
        $reflect->_removeTransition($key2);

        $this->assertTrue($reflect->_hasTransition($key1), 'Key 1 was not removed; should have returned true.');
        $this->assertFalse($reflect->_hasTransition($key2), 'Key 2 was removed; should have returned false.');
        $this->assertTrue($reflect->_hasTransition($key3), 'Key 3 was not removed; should have returned true.');
    }

    /**
     * Tests the transition adder, reset and getter methods to ensure that added transitions are correctly reset.
     *
     * @since [*next-version*]
     */
    public function testAddResetGetTransitions()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $transitions = [
            $key1 = uniqid('transition-'),
            $this->createStringable($key2 = uniqid('transition-')),
            $this->createStringable($key3 = uniqid('transition-')),
        ];

        $reflect->_addTransitions($transitions);
        $reflect->_resetTransitions();

        $this->assertEmpty($reflect->_getTransitions(), 'Transitions were cleared; should be empty.');
    }

    /**
     * Tests the transition adder method with an invalid transition to ensure that an exception is thrown and that the
     * invalid value is not added to the list.
     *
     * @since [*next-version*]
     */
    public function testAddTransitionInvalid()
    {
        $subject = $this->createInstance(['_createInvalidArgumentException', '__']);
        $reflect = $this->reflect($subject);

        $subject->expects($this->once())
                ->method('__')
                ->with($this->isType('string'))
                ->willReturnArgument(0);

        $subject->expects($this->once())
                ->method('_createInvalidArgumentException')
                ->with($this->isType('string'))
                ->willReturnCallback(
                    function ($message) {
                        return new Exception($message);
                    }
                );

        $this->setExpectedException('Exception');

        $reflect->_addTransition($invalid = new \stdClass());

        $this->assertFalse(
            array_search($invalid, $reflect->_getTransitions()),
            'Invalid transition should not be added.'
        );
    }

    /**
     * Tests the transition setter method to ensure that existing transitions are correctly reset and the new
     * transitions correctly added.
     *
     * @since [*next-version*]
     */
    public function testSetTransitions()
    {
        $subject = $this->createInstance(['_resetTransitions', '_addTransitions']);
        $reflect = $this->reflect($subject);

        $transitions = [
            $key1 = uniqid('transition-'),
            $this->createStringable($key2 = uniqid('transition-')),
            $this->createStringable($key3 = uniqid('transition-')),
        ];

        $subject->expects($this->once())
                ->method('_resetTransitions');
        $subject->expects($this->once())
                ->method('_addTransitions')
                ->with($transitions);

        $reflect->_setTransitions($transitions);
    }
}
