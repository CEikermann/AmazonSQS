<?php

/**
 * This file is part of the AmazonSQS package.
 *
 * (c) Christian Eikermann <christian@chrisdev.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\AmazonSQS\Model;

use AmazonSQS\Model\DeleteMessageBatchResultEntry;

class DeleteMessageBatchResultEntryTest extends \PHPUnit_Framework_TestCase
{
    public function dpSetAndGetTest()
    {
        return array(
            array('Id',1),
            array('Code',2),
            array('Message','Test'),
            array('SenderFault','FAULT')
        );
    }
    
    /**
     * @dataProvider dpSetAndGetTest
     */
    public function testSetAndGet($name, $value)
    {
        $entity = new DeleteMessageBatchResultEntry();

        $method = 'set' . $name;
        $entity->$method($value);

        $method = 'get' . $name;
        $this->assertEquals($value, $entity->$method(), 'Wrong value with ' . $name);
    }

    public function testFromArray() {
        $entity = new DeleteMessageBatchResultEntry();
        $entity->fromArray(array(
            'Id' => 1,
            'Code' => 2,
            'Message' => 'Failed',
            'SenderFault' => TRUE
        ));       

        $this->assertEquals(1, $entity->getId());
        $this->assertEquals(2, $entity->getCode());
        $this->assertEquals('Failed', $entity->getMessage());
        $this->assertEquals(TRUE, $entity->getSenderFault());
    }

    public function testConstructsFromArray() {
        $entity = new DeleteMessageBatchResultEntry(array(
            'Id' => 1,
            'Code' => 2,
            'Message' => 'Failed',
            'SenderFault' => TRUE
        ));       

        $this->assertEquals(1, $entity->getId());
        $this->assertEquals(2, $entity->getCode());
        $this->assertEquals('Failed', $entity->getMessage());
        $this->assertEquals(TRUE, $entity->getSenderFault());
    }

    public function testRecognisesNotFailed() 
    {
        $entity = new DeleteMessageBatchResultEntry();
        $entity->setId(1);

        $this->assertEquals(FALSE, $entity->isDeletionFailure());        
    }

    public function dpTestRecognisesFailed()
    {
        return array(
            array('Code',2),
            array('Message','Test'),
            array('SenderFault',FALSE)
        );
    }
    
    /**
     * @dataProvider dpTestRecognisesFailed
     */
    public function testRecognisesFailed($name, $value) 
    {
        $entity = new DeleteMessageBatchResultEntry();
        $method = 'set' . $name;
        $entity->$method($value);

        $this->assertEquals(TRUE, $entity->isDeletionFailure());        
    }    
}
