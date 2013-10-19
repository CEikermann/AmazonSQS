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

use AmazonSQS\Model\DeleteMessageBatchResult;

class DeleteMessageBatchResultTest extends \PHPUnit_Framework_TestCase
{
    function testSucceededWithNoResponse() {
        $result = new DeleteMessageBatchResult(NULL);
        $this->assertEquals(FALSE, $result->succeeded());
    }

    function testSucceededWithPositiveResponse() {
        $result = new DeleteMessageBatchResult(array(
            array('Id' => 1),
            array('Id' => 2),
            array('Id' => 3)
        ));
        
        $this->assertEquals(FALSE, $result->containsFailedEntries());
        $this->assertEquals(3, $result->getNumberOfEntries());
        $this->assertEquals(TRUE, $result->succeeded());
    }

    function testGetFailedEntriesNoFailures() {
        $result = new DeleteMessageBatchResult(array(
            array('Id' => 1),
            array('Id' => 2),
            array('Id' => 3)
        ));
        
        $this->assertEquals(array(), $result->getFailedEntries());
    }

    function testGetFailedEntriesWithFailures() {
        $result = new DeleteMessageBatchResult(array(
            array('Id' => 1, 'Code' => 1),
            array('Id' => 2, 'Message' => 'Foo'),
            array('Id' => 3, 'SenderFault' => TRUE)
        ));
        
        $failures = $result->getFailedEntries();
        $this->assertEquals(3, count($failures));

        $this->assertEquals(1, $failures[0]->getId());
        $this->assertEquals(1, $failures[0]->getCode());

        $this->assertEquals(2, $failures[1]->getId());
        $this->assertEquals('Foo', $failures[1]->getMessage());

        $this->assertEquals(3, $failures[2]->getId());
        $this->assertEquals(TRUE, $failures[2]->getSenderFault());        
    }
}
