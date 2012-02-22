<?php

namespace AmazonSQS\Model;

use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;

/**
 * Description of Queue
 *
 * @author Christian Eikermann <christian@chrisdev.de>
 */
class Queue
{

    private $name;
    private $url;
    private $visibilityTimeout;
    private $approximateNumberOfMessages;
    private $approximateNumberOfMessagesNotVisible;
    private $createdTimestamp;
    private $lastModifiedTimestamp;
    private $queueArn;
    private $maximumMessageSize;
    private $messageRetentionPeriod;
    private $policy;
    private $delaySeconds;

    public function getUniqueKey()
    {
        return sha1($this->getUrl());
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        if (isset($this->name)) {
            throw new \RuntimeException('Field "Queue::Name" is not editable!');
        }

        $this->name = $name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        if (isset($this->url)) {
            throw new \RuntimeException('Field "Queue::Url" is not editable!');
        }

        $this->url = $url;
    }

    public function getApproximateNumberOfMessages()
    {
        return $this->approximateNumberOfMessages;
    }

    public function setApproximateNumberOfMessages($approximateNumberOfMessages)
    {
        if (isset($this->approximateNumberOfMessages)) {
            throw new \RuntimeException('Field "Queue::ApproximateNumberOfMessages" is not editable!');
        }

        $this->approximateNumberOfMessages = $approximateNumberOfMessages;
    }

    public function getApproximateNumberOfMessagesNotVisible()
    {
        return $this->approximateNumberOfMessagesNotVisible;
    }

    public function setApproximateNumberOfMessagesNotVisible($approximateNumberOfMessagesNotVisible)
    {
        if (isset($this->approximateNumberOfMessagesNotVisible)) {
            throw new \RuntimeException('Field "Queue::ApproximateNumberOfMessagesNotVisible" is not editable!');
        }

        $this->approximateNumberOfMessagesNotVisible = $approximateNumberOfMessagesNotVisible;
    }

    public function getCreatedTimestamp()
    {
        return $this->createdTimestamp;
    }

    public function setCreatedTimestamp($createdTimestamp)
    {
        if (isset($this->createdTimestamp)) {
            throw new \RuntimeException('Field "Queue::CreatedTimestamp" is not editable!');
        }

        $this->createdTimestamp = $createdTimestamp;
    }

    public function getLastModifiedTimestamp()
    {
        return $this->lastModifiedTimestamp;
    }

    public function setLastModifiedTimestamp($lastModifiedTimestamp)
    {
        if (isset($this->lastModifiedTimestamp)) {
            throw new \RuntimeException('Field "Queue::LastModifiedTimestamp" is not editable!');
        }

        $this->lastModifiedTimestamp = $lastModifiedTimestamp;
    }

    public function getQueueArn()
    {
        return $this->queueArn;
    }

    public function setQueueArn($queueArn)
    {
        if (isset($this->queueArn)) {
            throw new \RuntimeException('Field "Queue::QueueArn" is not editable!');
        }

        $this->queueArn = $queueArn;
    }

    public function getVisibilityTimeout()
    {
        return $this->visibilityTimeout;
    }

    public function setVisibilityTimeout($visibilityTimeout)
    {
        $this->visibilityTimeout = $visibilityTimeout;
    }

    public function getMaximumMessageSize()
    {
        return $this->maximumMessageSize;
    }

    public function setMaximumMessageSize($maximumMessageSize)
    {
        $this->maximumMessageSize = $maximumMessageSize;
    }

    public function getMessageRetentionPeriod()
    {
        return $this->messageRetentionPeriod;
    }

    public function setMessageRetentionPeriod($messageRetentionPeriod)
    {
        $this->messageRetentionPeriod = $messageRetentionPeriod;
    }

    public function getPolicy()
    {
        return $this->policy;
    }

    public function setPolicy($policy)
    {
        $this->policy = $policy;
    }

    public function getDelaySeconds()
    {
        return $this->delaySeconds;
    }

    public function setDelaySeconds($delaySeconds)
    {
        $this->delaySeconds = $delaySeconds;
    }

}