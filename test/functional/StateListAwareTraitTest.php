<?php

namespace Dhii\State\FuncTest;

use Dhii\Util\String\StringableInterface;
use Exception;
use PHPUnit_Framework_MockObject_MockObject;
use Xpmock\TestCase;

/**
 * Tests {@see Dhii\State\StateListAwareTrait}.
 *
 * @since [*next-version*]
 */
class StateListAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\State\StateListAwareTrait';

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
     * Tests the state key getter method.
     *
     * @since [*next-version*]
     */
    public function testGetStateKey()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $state = uniqid('state-');

        $this->assertEquals(
            $state,
            $reflect->_getStateKey($state),
            'Retrieved state key and given state string are not the same.'
        );
    }

    /**
     * Tests the state key getter method with a stringable state instance.
     *
     * @since [*next-version*]
     */
    public function testGetStateKeyStringable()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $state = $this->createStringable($key = uniqid('state-'));

        $this->assertEquals(
            $key,
            $reflect->_getStateKey($state),
            'Retrieved state key and key of given state instance are not the same.'
        );
    }

    /**
     * Tests the state adder and getter methods to ensure that correct state adding and retrieval.
     *
     * @since [*next-version*]
     */
    public function testAddGetState()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);
        $state   = uniqid('state-');

        $reflect->_addState($state);

        $this->assertContains($state, $reflect->_getStates(), 'Retrieved state list does not have state.');
        $this->assertArrayHasKey($state, $reflect->_getStates(), 'Retrieved state list does not have state key.');
        $this->assertEquals($state, $reflect->_getState($state), 'Retrieved and added states are not the same.');
    }

    /**
     * Tests the state adder and getter methods with a stringable state instance.
     *
     * @since [*next-version*]
     */
    public function testAddGetStateStringable()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);
        $key     = uniqid('state-');
        $state   = $this->createStringable($key);

        $reflect->_addState($state);

        $list = $reflect->_getStates();

        $this->assertArrayHasKey($key, $list, 'Retrieved state list does not have state key.');
        $this->assertSame($state, $list[$key], 'State in list is not same as given stringable instance.');
        $this->assertEquals(
            $state,
            $reflect->_getState($key),
            'State retrieved by key and added state are not the same.'
        );
        $this->assertEquals(
            $state,
            $reflect->_getState($state),
            'State retrieved by instance and added state are not the same.'
        );
    }

    /**
     * Tests the multiple state adder method and the getter method to ensure that all states are correctly
     * added and retrieved.
     *
     * @since [*next-version*]
     */
    public function testAddGetStates()
    {
        $subject = $this->createInstance(['_addState']);

        $reflect = $this->reflect($subject);
        $states  = [
            uniqid('state-'),
            uniqid('state-'),
            $this->createStringable(uniqid('state-')),
        ];

        $subject->expects($this->exactly(3))
                ->method('_addState')
                ->withConsecutive([$states[0]], [$states[1]], [$states[2]]);

        $reflect->_addStates($states);
    }

    /**
     * Tests the state adder and checker methods to ensure that correct state adding and existence checking.
     *
     * @since [*next-version*]
     */
    public function testAddHasState()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $state = uniqid('state-');

        $reflect->_addState($state);

        $this->assertTrue($reflect->_hasState($state), 'State was added, should have returned true.');
    }

    /**
     * Tests the state adder and checker methods with a stringable state instance.
     *
     * @since [*next-version*]
     */
    public function testAddHasStateStringable()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $key   = uniqid('state-');
        $state = $this->createStringable($key);

        $reflect->_addState($state);

        $this->assertTrue($reflect->_hasState($key), 'State with key was added, should have returned true');
        $this->assertTrue($reflect->_hasState($state), 'State was added, should have returned true.');
    }

    /**
     * Tests the state adder, removal and checker methods to ensure that added states are correctly removed.
     *
     * @since [*next-version*]
     */
    public function testAddRemoveHasState()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $states = [
            $key1 = uniqid('state-'),
            $this->createStringable($key2 = uniqid('state-')),
            $this->createStringable($key3 = uniqid('state-')),
        ];

        $reflect->_addStates($states);
        $reflect->_removeState($key2);

        $this->assertTrue($reflect->_hasState($key1), 'Key 1 was not removed; should have returned true.');
        $this->assertFalse($reflect->_hasState($key2), 'Key 2 was removed; should have returned false.');
        $this->assertTrue($reflect->_hasState($key3), 'Key 3 was not removed; should have returned true.');
    }

    /**
     * Tests the state adder, reset and getter methods to ensure that added states are correctly reset.
     *
     * @since [*next-version*]
     */
    public function testAddResetGetStates()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $states = [
            $key1 = uniqid('state-'),
            $this->createStringable($key2 = uniqid('state-')),
            $this->createStringable($key3 = uniqid('state-')),
        ];

        $reflect->_addStates($states);
        $reflect->_resetStates();

        $this->assertEmpty($reflect->_getStates(), 'States were cleared; should be empty.');
    }

    /**
     * Tests the state adder method with an invalid state to ensure that an exception is thrown and that the
     * invalid value is not added to the list.
     *
     * @since [*next-version*]
     */
    public function testAddStateInvalid()
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
                    function($message) {
                        return new Exception($message);
                    }
                );

        $this->setExpectedException('Exception');

        $reflect->_addState($invalid = new \stdClass());

        $this->assertFalse(array_search($invalid, $reflect->_getStates()), 'Invalid state should not be added.');
    }

    /**
     * Tests the state setter method to ensure that existing states are correctly reset and the new states
     * correctly added.
     *
     * @since [*next-version*]
     */
    public function testSetStates()
    {
        $subject = $this->createInstance(['_resetStates', '_addStates']);
        $reflect = $this->reflect($subject);

        $states = [
            $key1 = uniqid('state-'),
            $this->createStringable($key2 = uniqid('state-')),
            $this->createStringable($key3 = uniqid('state-')),
        ];

        $subject->expects($this->once())
                ->method('_resetStates');
        $subject->expects($this->once())
                ->method('_addStates')
                ->with($states);

        $reflect->_setStates($states);
    }
}
