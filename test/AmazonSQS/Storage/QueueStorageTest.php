<?php

namespace Test\AmazonSQS\Storage;

use AmazonSQS\Storage\QueueStorage;
use AmazonSQS\Model\Queue;

class QueueStorageTest extends \PHPUnit_Framework_TestCase
{
    
    public function testAddAndGet()
    {
        $queue = new Queue();
        $queue->setUrl('Testurl!');
        
        $queueStorage = new QueueStorage();
        $queueStorage->add($queue);
        
        $actual = $queueStorage->get($queue);
        $this->assertEquals($queue, $actual, 'Queue not equals');
    }
    
    public function testExists()
    {
        $queue = new Queue();
        $queue->setUrl('Testurl!');
        
        $queueStorage = new QueueStorage();
        $queueStorage->add($queue);
        
        $this->assertTrue($queueStorage->exists($queue), 'Queue should be exists');
        
        $queue = new Queue();
        $queue->setUrl('Blub');
        
        $this->assertFalse($queueStorage->exists($queue), 'Queue should be not exists');
    }
    
    public function testAddAndGetClone()
    {
        $queue = new Queue();
        $queue->setUrl('Testurl!');
        $queue->setDelaySeconds(300);
        
        $queueStorage = new QueueStorage();
        $queueStorage->add($queue);
        
        $expected = clone $queue;
        $queue->setDelaySeconds(500);
        
        $actual = $queueStorage->get($queue);
        $this->assertEquals($expected, $actual, 'Queue not equals');
    }
    
    public function testGetNotExists()
    {
        $queue = new Queue();
        $queue->setUrl('Testurl!');
        $queue->setDelaySeconds(300);
        
        $queueStorage = new QueueStorage();
        $this->assertNull($queueStorage->get($queue), 'Queue does not exists');
    }
    
    public function testRemove()
    {
        $queue = new Queue();
        $queue->setUrl('Testurl!');
        
        $queueStorage = new QueueStorage();
        $queueStorage->add($queue);
        $queueStorage->remove($queue);
        
        $this->assertFalse($queueStorage->exists($queue), 'Queue should be not exists');
    }
}

?>
