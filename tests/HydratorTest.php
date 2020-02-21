<?php
namespace samdark\hydrator\tests;
use samdark\hydrator\Hydrator;

class HydratorTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrate()
    {
        $hydrator = new Hydrator([
            'privateField' => 'privateField',
            'protectedField' => 'protectedField',
            'publicField' => 'publicField',
        ]);

        /** @var TestClass $testObject */
        $testObject = $hydrator->hydrate([
            'privateField' => 1,
            'protectedField' => 2,
            'publicField' => 3,
        ], 'samdark\hydrator\tests\TestClass');

        self::assertEquals(1, $testObject->getPrivateField());
        self::assertEquals(2, $testObject->getProtectedField());
        self::assertEquals(3, $testObject->publicField);
        self::assertFalse($testObject->getConstructorCalled());
    }

    public function testHydrateInto()
    {
        $hydrator = new Hydrator([
            'privateField' => 'privateField',
            'protectedField' => 'protectedField',
            'publicField' => 'publicField',
        ]);

        $testObject = new TestClass();
        $testObject = $hydrator->hydrateInto([
            'privateField' => 1,
            'protectedField' => 2,
            'publicField' => 3,
        ], $testObject);

        self::assertEquals(1, $testObject->getPrivateField());
        self::assertEquals(2, $testObject->getProtectedField());
        self::assertEquals(3, $testObject->publicField);
        self::assertTrue($testObject->getConstructorCalled());
    }
    
    public function testHydrateParentFields()
    {
        $hydrator = new Hydrator([
            'privateField' => 'privateField',
            'protectedField' => 'protectedField',
            'publicField' => 'publicField',
            'privateChildField' => 'privateChildField',
        ]);

        /** @var TestChildClass $testObject */
        $testObject = $hydrator->hydrate([
            'privateField' => 1,
            'protectedField' => 2,
            'publicField' => 3,
            'privateChildField' => 4,
        ], TestChildClass::class);

        self::assertEquals(1, $testObject->getPrivateField());
        self::assertEquals(2, $testObject->getProtectedField());
        self::assertEquals(3, $testObject->publicField);
        self::assertEquals(4, $testObject->getPrivateChildField());
        self::assertFalse($testObject->getConstructorCalled());
    }
    public function testExtract()
    {
        $testObject = new TestClass2(1, 2, 3);

        $hydrator = new Hydrator([
            'privateField' => 'privateField',
            'protectedField' => 'protectedField',
            'publicField' => 'publicField',
        ]);
        /** @var TestClass $testObject */
        $data = $hydrator->extract($testObject);

        self::assertEquals([
            'privateField' => 1,
            'protectedField' => 2,
            'publicField' => 3,
        ], $data);
    }
    
    public function testExtractParentFields()
    {
        $testObject = new TestChildClass2(1, 2, 3, 4);

        $hydrator = new Hydrator([
            'privateField' => 'privateField',
            'protectedField' => 'protectedField',
            'publicField' => 'publicField',
            'privateChildField' => 'privateChildField',
        ]);
        /** @var TestChildClass2 $testObject */
        $data = $hydrator->extract($testObject);

        self::assertEquals([
            'privateField' => 1,
            'protectedField' => 2,
            'publicField' => 3,
            'privateChildField' => 4,
        ], $data);
    }
}
