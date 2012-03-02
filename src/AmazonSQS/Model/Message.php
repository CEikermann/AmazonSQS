<?php

namespace AmazonSQS\Model;

/**
 * Description of Queue
 *
 * @author Christian Eikermann <christian@chrisdev.de>
 */
class Message
{
    private $queue;
    private $messageId;
    private $receiptHandle;
    private $md5OfBody;
    private $body;
    private $senderId;
    private $sendTimestamp;
    private $approximateReceiveCount;
    private $approximateFirstReceiveTimestamp;

    public function getQueue()
    {
        return $this->queue;
    }

    public function setQueue($queue)
    {
        $this->queue = $queue;
    }
    
    public function getMessageId()
    {
        return $this->messageId;
    }

    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    public function getReceiptHandle()
    {
        return $this->receiptHandle;
    }

    public function setReceiptHandle($receiptHandle)
    {
        $this->receiptHandle = $receiptHandle;
    }

    public function getMd5OfBody()
    {
        return $this->md5OfBody;
    }

    public function setMd5OfBody($md5OfBody)
    {
        $this->md5OfBody = $md5OfBody;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getSenderId()
    {
        return $this->senderId;
    }

    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
    }

    public function getSendTimestamp()
    {
        return $this->sendTimestamp;
    }

    public function setSendTimestamp($sendTimestamp)
    {
        $this->sendTimestamp = $sendTimestamp;
    }

    public function getApproximateReceiveCount()
    {
        return $this->approximateReceiveCount;
    }

    public function setApproximateReceiveCount($approximateReceiveCount)
    {
        $this->approximateReceiveCount = $approximateReceiveCount;
    }

    public function getApproximateFirstReceiveTimestamp()
    {
        return $this->approximateFirstReceiveTimestamp;
    }

    public function setApproximateFirstReceiveTimestamp($approximateFirstReceiveTimestamp)
    {
        $this->approximateFirstReceiveTimestamp = $approximateFirstReceiveTimestamp;
    }
    
}
