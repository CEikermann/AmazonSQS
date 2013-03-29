<?php

/**
 * This file is part of the AmazonSQS package.
 *
 * (c) Christian Eikermann <christian@chrisdev.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AmazonSQS\Model;

use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;

/**
 * Model class for Queue objects
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
    private $receiveMessageWaitTimeSeconds;

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
        $this->name = $name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getApproximateNumberOfMessages()
    {
        return $this->approximateNumberOfMessages;
    }

    public function setApproximateNumberOfMessages($approximateNumberOfMessages)
    {
        $this->approximateNumberOfMessages = $approximateNumberOfMessages;
    }

    public function getApproximateNumberOfMessagesNotVisible()
    {
        return $this->approximateNumberOfMessagesNotVisible;
    }

    public function setApproximateNumberOfMessagesNotVisible($approximateNumberOfMessagesNotVisible)
    {
        $this->approximateNumberOfMessagesNotVisible = $approximateNumberOfMessagesNotVisible;
    }

    public function getCreatedTimestamp()
    {
        return $this->createdTimestamp;
    }

    public function setCreatedTimestamp($createdTimestamp)
    {
        $this->createdTimestamp = $createdTimestamp;
    }

    public function getLastModifiedTimestamp()
    {
        return $this->lastModifiedTimestamp;
    }

    public function setLastModifiedTimestamp($lastModifiedTimestamp)
    {
        $this->lastModifiedTimestamp = $lastModifiedTimestamp;
    }

    public function getQueueArn()
    {
        return $this->queueArn;
    }

    public function setQueueArn($queueArn)
    {
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

    public function getReceiveMessageWaitTimeSeconds()
    {
        return $this->receiveMessageWaitTimeSeconds;
    }

    public function setReceiveMessageWaitTimeSeconds($waitTimeSeconds)
    {
        $this->receiveMessageWaitTimeSeconds = $waitTimeSeconds;
    }

}