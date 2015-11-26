<?php
namespace Classes\Instance\Node;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-21 at 11:57:15.
 */
class NodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Value
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $value = new \Classes\Value\FloatValue(3.5);
        
        $this->object = new Node();
        $this->object->setProperty('x', $value);
    }

    /**
     * @covers Classes\Instance\Node\Node::getProperty
     */
    public function testGetProperty_1()
    {
        $value = new \Classes\Value\FloatValue(3.5);
        
        $this->assertEquals($this->object->getProperty('x'), $value);
    }
    
     /**
     * @covers Classes\Instance\Node\Node::getProperty
     */
    public function testGetProperty_2()
    {
        $this->assertEquals($this->object->getProperty('wrongProperty'), NULL);
    }
    
    /**
     * @covers Classes\Instance\Node\Node::setProperty
     */
    public function testSetProperty_1()
    {
        $value = new \Classes\Value\FloatValue(5.5);
        $this->object->setProperty('x', $value);
        
        $this->assertEquals($this->object->getProperty('x'), $value);
    }
    
    /**
     * @covers Classes\Instance\Node\Node::setProperty
     * 
     * @expectedException \Classes\Exception\Instance\InvalidPropertyNameException
     */
    public function testSetProperty_2()
    {
        $value = new \Classes\Value\FloatValue(5.5);
        $this->object->setProperty('wrongProperty', $value);
    }
    
    /**
     * @covers Classes\Instance\Node\Node::setProperty
     * 
     * @expectedException \Classes\Exception\Instance\InvalidArgumentException
     */
    public function testSetProperty_3()
    {
        $this->object->setProperty('x', 'value');
    }
    
    /**
     * @covers Classes\Instance\Node\Node::setProperty
     * 
     * @expectedException \Classes\Exception\Instance\InvalidArgumentException
     */
    public function testSetProperty_4()
    {
        $this->object->setProperty();
    }
    
    /**
     * @covers Classes\Instance\Node\Node::compare
     */
    public function testCompare_1()
    {
        $a = new Node();
        $a->setProperty('x',new \Classes\Value\FloatValue(1));
        $a->setProperty('y',new \Classes\Value\FloatValue(2));
        $a->setProperty('z',new \Classes\Value\FloatValue(3));
        
        $b = new Node();
        $b->setProperty('x',new \Classes\Value\FloatValue(1.05));
        $b->setProperty('y',new \Classes\Value\FloatValue(1.94));
        $b->setProperty('z',new \Classes\Value\FloatValue(3.01));

        $tolerance = 0.1;
        
        $this->assertEquals(Node::compare($a, $b, $tolerance), 0);
    }
    
    /**
     * @covers Classes\Instance\Node\Node::compare
     */
    public function testCompare_2()
    {
        $a = new Node();
        $a->setProperty('x',new \Classes\Value\FloatValue(1.5));
        $a->setProperty('y',new \Classes\Value\FloatValue(2));
        $a->setProperty('z',new \Classes\Value\FloatValue(3));
        
        $b = new Node();
        $b->setProperty('x',new \Classes\Value\FloatValue(1));
        $b->setProperty('y',new \Classes\Value\FloatValue(1.5));
        $b->setProperty('z',new \Classes\Value\FloatValue(1.5));

        $tolerance = 0.1;
        
        $this->assertEquals(Node::compare($a, $b, $tolerance), 1);
    }
    
    /**
     * @covers Classes\Instance\Node\Node::compare
     */
    public function testCompare_3()
    {
        $a = new Node();
        $a->setProperty('x',new \Classes\Value\FloatValue(1));
        $a->setProperty('y',new \Classes\Value\FloatValue(0));
        $a->setProperty('z',new \Classes\Value\FloatValue(2));
        
        $b = new Node();
        $b->setProperty('x',new \Classes\Value\FloatValue(1));
        $b->setProperty('y',new \Classes\Value\FloatValue(1));
        $b->setProperty('z',new \Classes\Value\FloatValue(1));

        $tolerance = 0.1;
        
        $this->assertEquals(Node::compare($a, $b, $tolerance), -1);
    }
    
    /**
     * @covers Classes\Instance\Node\Node::compare
     */
    public function testCompare_4()
    {
        $a = new Node();
        $a->setProperty('x',new \Classes\Value\FloatValue(1));
        $a->setProperty('y',new \Classes\Value\FloatValue(1));
        $a->setProperty('z',new \Classes\Value\FloatValue(2));
        
        $b = new Node();
        $b->setProperty('x',new \Classes\Value\FloatValue(1));
        $b->setProperty('y',new \Classes\Value\FloatValue(1));
        $b->setProperty('z',new \Classes\Value\FloatValue(1));

        $tolerance = 0.1;
        
        $this->assertEquals(Node::compare($a, $b, $tolerance), 1);
    }
    
    

    
}
