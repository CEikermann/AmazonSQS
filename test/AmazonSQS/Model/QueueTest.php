<?php

namespace Test\AmazonSQS\Model;

use AmazonSQS\Model\Message;
use AmazonSQS\Model\Queue;

class MessageTest extends \PHPUnit_Framework_TestCase
{

    public function dpSetAndGetTest()
    {
        return array(
            array('Queue', new Queue()),
            array('MessageId', '123'),
            array('ReceiptHandle', 'abc123'),
            array('Md5OfBody', 'eff123'),
            array('Body', 'raw body'),
            array('SenderId', '567'),
            array('SendTimestamp', '123456789'),
            array('ApproximateReceiveCount', '1'),
            array('ApproximateFirstReceiveTimestamp', '987654321'),
        );
    }
    
    /**
     * @dataProvider dpSetAndGetTest
     */
    public function testSetAndGet($name, $value)
    {
        $message = new Message();

        $method = 'set' . $name;
        $message->$method($value);

        $method = 'get' . $name;
        $this->assertEquals($value, $message->$method(), 'Wrong value with ' . $name);
    }
}
