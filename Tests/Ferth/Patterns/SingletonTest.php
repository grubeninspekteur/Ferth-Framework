<?php

namespace Ferth\Patterns;

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__) . '/../../../Ferth/Patterns/Singleton.php';

class SingletonTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Singleton
     */
    protected $object;

    /**
     * Tests __invoke, getInstance, class name object creation and singleton
     * pattern realization.
     */
    public function testClassName()
    {
        $singleton = new Singleton('\stdClass');

        $object1 = $singleton();
        $object1->test = 42;
        $object2 = $singleton->getInstance();

        $this->assertEquals($object1->test, $object2->test);
    }

    public function testClosure()
    {
        $singleton = new Singleton(function () {return new \stdClass;});

        $object1 = $singleton();
        $object1->test = 42;
        $object2 = $singleton->getInstance();

        $this->assertEquals($object1->test, $object2->test);
    }

}

?>
