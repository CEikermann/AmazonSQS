<?php

namespace Test\AmazonSQS\Model;

use AmazonSQS\Model\Message;
use AmazonSQS\Model\Queue;

class QueueTest extends \PHPUnit_Framework_TestCase
{

    public function dpSetAndGetTest()
    {
        return array(
            array('Name', 'QueueName123'),
            array('Url', 'QueueUrl123'),
            array('VisibilityTimeout', '123'),
            array('ApproximateNumberOfMessages', '1'),
            array('ApproximateNumberOfMessagesNotVisible', '2'),
            array('CreatedTimestamp', '123456789'),
            array('LastModifiedTimestamp', '987654321'),
            array('QueueArn', 'queue:scope'),
            array('MaximumMessageSize', '1024'),
            array('Policy', 'blub'),
            array('DelaySeconds', '1024'),
        );
    }
    
    /**
     * @dataProvider dpSetAndGetTest
     */
    public function testSetAndGet($name, $value)
    {
        $queue = new Queue();

        $method = 'set' . $name;
        $queue->$method($value);

        $method = 'get' . $name;
        $this->assertEquals($value, $queue->$method(), 'Wrong value with ' . $name);
    }
}
