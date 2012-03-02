<?php

/**
 * This file is part of the AmazonSQS package.
 *
 * (c) Christian Eikermann <christian@chrisdev.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AmazonSQS\Storage;

use AmazonSQS\Model\Queue;

/**
 * Class to store cloned Queue objects
 *
 * @author Christian Eikermann <christian@chrisdev.de>
 */
class QueueStorage
{

    /**
     * Queue storage
     * 
     * @var array 
     */
    private $storage = array();

    /**
     * Add a Queue object to the storage
     * 
     * @param \AmazonSQS\Model\Queue $queue Queue object
     * 
     * @return void
     */
    public function add(Queue $queue)
    {
        $this->storage[$queue->getUniqueKey()] = clone $queue;
    }

    /**
     * Return the Queue object out of the storage
     * 
     * @param \AmazonSQS\Model\Queue $queue Queue object
     * 
     * @return \AmazonSQS\Model\Queue|null 
     */
    public function get(Queue $queue)
    {
        return $this->exists($queue) ? $this->storage[$queue->getUniqueKey()] : null;
    }

    /**
     * Remove a Queue object out of the storage
     * 
     * @param \AmazonSQS\Model\Queue $queue 
     * 
     * @return void
     */
    public function remove(Queue $queue)
    {
        if ($this->exists($queue)) {
            unset($this->storage[$queue->getUniqueKey()]);
        }
    }

    /**
     * Exists a Queue object in the storage
     * 
     * @param \AmazonSQS\Model\Queue $queue Queue object
     * 
     * @return bool 
     */
    public function exists(Queue $queue)
    {
        return isset($this->storage[$queue->getUniqueKey()]);
    }

}
