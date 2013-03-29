<?php

/**
 * This file is part of the AmazonSQS package.
 *
 * (c) Christian Eikermann <christian@chrisdev.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AmazonSQS;

use AmazonSQS\Storage\QueueStorage;
use AmazonSQS\Model\Queue;
use AmazonSQS\Model\Message;

use Symfony\Component\Serializer;

/**
 * Manager class for AmazonSQS API 
 *
 * @author Christian Eikermann <christian@chrisdev.de>
 */
class Manager
{
    const REGION_US_EAST_1      = 'us-east-1';
    const REGION_US_WEST_1      = 'us-west-1';
    const REGION_US_WEST_2      = 'us-west-2';
    const REGION_EU_WEST_1      = 'eu-west-1';
    const REGION_AP_SOUTHEAST_1 = 'ap-southeast-1';
    const REGION_AP_NORTHEAST_1 = 'ap-northeast-1';
    const REGION_SA_EAST_1      = 'sa-east-1';

    /**
     * AWS Access Key
     * 
     * @var string
     */
    protected $awsAccessKey = null;

    /**
     * AWS Secret Key
     *  
     * @var string 
     */
    protected $awsSecretKey = null;

    /**
     * Amazon SQS API endpoint
     *
     * @var string 
     */
    protected $endpoint = 'sqs.%s.amazonaws.com';

    /**
     * Region
     * 
     * @var string 
     */
    protected $region = self::REGION_EU_WEST_1;
    
    /**
     * Editable queue fields
     * 
     * @var array 
     */
    protected $editableQueueFields = array('policy', 'maximumMessageSize', 'messageRetentionPeriod', 'delaySeconds');
    
    /**
     * AmazonSQS apiTalk client
     * 
     * @var \AmazonSQS\Client 
     */
    private $client = null;

    /**
     * Symfony serializer
     * 
     * @var \Symfony\Component\Serializer\Serializer 
     */
    private $serializer = null;

    /**
     * Queue storage
     * 
     * @var \AmazonSQS\Storage\QueueStorage 
     */
    private $queueStorage = null;
    
    /**
     * Constructor
     * 
     * @param string $awsAccessKey AWS Access Key
     * @param string $awsSecretKey AWS Secret Key
     * @param string $region       Region
     */
    public function __construct($awsAccessKey, $awsSecretKey, $region = self::REGION_EU_WEST_1)
    {
        $this->awsAccessKey = $awsAccessKey;
        $this->awsSecretKey = $awsSecretKey;
        $this->region = $region;
    }

    /**
     * Return the instance of AmazonSQS apiTalk client
     * 
     * @return \AmazonSQS\Client  
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Client($this->awsAccessKey, $this->awsSecretKey);
        }

        return $this->client;
    }

    /**
     * Set the instance of AmazonSQS apiTalk client
     * 
     * @param \AmazonSQS\Client $client apiTalk client
     * 
     * @return void
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Return the instance of Symfony Serializer
     * 
     * @return \Symfony\Component\Serializer\Serializer
     */
    public function getSerializer()
    {
        if (!$this->serializer) {
            $this->serializer = new Serializer\Serializer(array(new Serializer\Normalizer\GetSetMethodNormalizer()));
        }

        return $this->serializer;
    }

    /**
     * Set the instance of Symfony Serializer
     * 
     * @param \Symfony\Component\Serializer\Serializer $serializer
     * 
     * @return void 
     */
    public function setSerializer(Serializer\Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Return a instance of the Queue storage
     * 
     * @return \AmazonSQS\Storage\QueueStorage 
     */
    public function getQueueStorage()
    {
        if (!$this->queueStorage) {
            $this->queueStorage = new QueueStorage();
        }
        
        return $this->queueStorage;
    }

    /**
     * Set the instance of the Queue storage
     * 
     * @param \AmazonSQS\Storage\QueueStorage $queueStorage 
     * 
     * @return void
     */
    public function setQueueStorage(QueueStorage $queueStorage)
    {
        $this->queueStorage = $queueStorage;
    }
    
    /**
     * Return the endpoint of Amazon SQS API
     * 
     * @return string 
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set the endpoint of Amazon SQS API
     * 
     * @param string $endpoint Endpoint
     * 
     * @return void
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Return region
     * 
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set region
     * 
     * @param string $region
     * 
     * @return void
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * Return the full API Url with endpoint and region
     * 
     * @return string 
     */
    public function getUrl()
    {
        return 'https://' . sprintf($this->getEndpoint(), $this->getRegion());
    }

    /**
     * Returns a list with queues
     * 
     * @param string $namePrefix (Optional) Queue name prefix (null = all)
     * 
     * @return array List of Queue objects
     */
    public function getQueues($namePrefix = null, $loadAttributes = false)
    {
        $params = array();
        if ($namePrefix) {
            $params['QueueNamePrefix'] = $namePrefix;
        }

        $response = $this->call('ListQueues', $params);
        if (!isset($response['QueueUrl']) || !is_array($response['QueueUrl'])) {
            return array();
        }

        $queues = array();
        foreach ($response['QueueUrl'] as $url) {
            $queues[] = $this->getQueueByUrl($url, $loadAttributes);
        }

        return $queues;
    }

    /**
     * Return one queue url by queue name
     * 
     * @param string $name 
     * 
     * @return \AmazonSQS\Model\Queue
     */
    public function getQueueByName($name, $loadAttributes = true)
    {
        $params = array();
        $params['QueueName'] = $name;

        $response = $this->call('GetQueueUrl', $params);
        
        if (!isset($response['QueueUrl'])) {
            return null;
        }

        return $this->getQueueByUrl($response['QueueUrl'], $loadAttributes);
    }

    /**
     * Return one queue by url
     * 
     * @param string $url            Queue url
     * @param bool   $loadAttributes (Optional) Load queue attributes - Default: true
     * 
     * @return \AmazonSQS\Model\Queue
     */
    public function getQueueByUrl($url, $loadAttributes = true)
    {
        $queue = new Queue();
        $queue->setName(substr($url, strrpos($url, '/') + 1));
        $queue->setUrl($url);

        if ($loadAttributes) {
            $queue = $this->loadQueueAttributes($queue);
        }

        return $queue;
    }

    /**
     * Create a new queue
     * Required fields: name
     * 
     * @param \AmazonSQS\Model\Queue $queue
     * 
     * @return \AmazonSQS\Model\Queue
     */
    public function createQueue(Queue $queue)
    {
        $params = array();
        $params['QueueName'] = $queue->getName();

        $attributeIndex = 0;
        $data = $this->getSerializer()->normalize($queue);
        foreach ($data as $key => $value) {
            if (in_array($key, $this->editableQueueFields) && isset($data[$key])) {
                $attributePrefix = sprintf('Attribute.%d.', ++$attributeIndex);

                $params[$attributePrefix . 'Name'] = ucfirst($key);
                $params[$attributePrefix . 'Value'] = $value;
            }
        }
        
        $response = $this->call('CreateQueue', $params);
        $queue->setUrl($response['QueueUrl']);

        return $queue;
    }

    /**
     * Updates all attributes of the queue
     * Required fields: name
     * 
     * @param \AmazonSQS\Model\Queue $queue
     * 
     * @return \AmazonSQS\Model\Queue
     */
    public function updateQueue(Queue $queue)
    {
        if (!$this->getQueueStorage()->exists($queue)) {
            throw new \RuntimeException('Unkown queue');
        }

        $newData = $this->getSerializer()->normalize($queue);
        $oldData = $this->getSerializer()->normalize($this->getQueueStorage()->get($queue));

        $diffData = array_diff($newData, $oldData);

        $params = array();
        foreach ($diffData as $key => $value) {
            if (in_array($key, $this->editableQueueFields)) {
                $params['Attribute.Name'] = ucfirst($key);
                $params['Attribute.Value'] = $value;

                $this->call('SetQueueAttributes', $params, $queue->getUrl());
            }
        }

        $this->getQueueStorage()->add($queue);
        
        return $queue;
    }

    /**
     * Delete one queue
     * 
     * @param \AmazonSQS\Model\Queue $queue 
     * 
     * @return bool
     */
    public function deleteQueue(Queue $queue)
    {
        $success = $this->call('DeleteQueue', array(), $queue->getUrl());
        if ($success) {
            $this->getQueueStorage()->remove($queue);
        }

        return $success;
    }

    /**
     * Send one message to the queue
     * 
     * @param \AmazonSQS\Model\Queue   $queue
     * @param \AmazonSQS\Model\Message $message
     * 
     * @return bool
     */
    public function sendMessage(Queue $queue, Message $message)
    {
        $params = array();
        $params['MessageBody'] = urlencode($message->getBody());

        $response = $this->call('SendMessage', $params, $queue->getUrl());

        if (!isset($response['MessageId'])) {
            return false;
        }
        
        $message->setMessageId($response['MessageId']);
        
        return true;
    }

    /**
     * Update one message
     * 
     * @param \AmazonSQS\Model\Message $message
     * 
     * @return bool
     */
    // @codeCoverageIgnoreStart
    public function updateMessage(Message $message)
    {
        // TODO: ChangeMessageVisibility
    }
    // @codeCoverageIgnoreEnd
    
    /**
     * Receive one messages from queue
     * 
     * @param Queue $queue Queue
     * @param int   $visibilityTimeout     Visibility Timeout
     * @param bool  $loadMessageAttributes Load message attributes
     * 
     * @return \AmazonSQS\Model\Message
     */
    public function receiveMessage(Queue $queue, $visibilityTimeout = null, $loadMessageAttributes = false)
    {
        $params = array();
        
        if ($visibilityTimeout) {
            $params['VisibilityTimeout'] = $visibilityTimeout;
        }
        
        if ($loadMessageAttributes) {
            $params['AttributeName.1'] = 'All';
        }
        
        $response = $this->call('ReceiveMessage', $params, $queue->getUrl());
        if (!isset($response['Message'])) {
            // No message in queue
            return null;
        }
        
        $data = $response['Message'];
        if (isset($data['Attribute'])) {
            foreach ($data['Attribute'] as $attribute) {

                $key = lcfirst($attribute['Name']);
                $value = $attribute['Value'];

                $data[$key] = $value;
            }
            
            unset($data['Attribute']);
        }
        
        $message = $this->getSerializer()->denormalize($data, '\AmazonSQS\Model\Message');
        $message->setBody(urldecode($message->getBody()));
        $message->setQueue($queue);
        
        return $message;
    }

    /**
     * Delete one messages from queue
     * 
     * @param \AmazonSQS\Model\Message $message 
     * 
     * @return bool
     */
    public function deleteMessage(Message $message)
    {
        $params = array();
        $params['ReceiptHandle'] = $message->getReceiptHandle();
        
        return $this->call('DeleteMessage', $params, $message->getQueue()->getUrl());
    }

    /**
     * Load all attributes of the queue
     * 
     * @param \AmazonSQS\Model\Queue $queue
     * 
     * @return \AmazonSQS\Model\Queue
     */
    public function loadQueueAttributes(Queue $queue)
    {
        $params['AttributeName.1'] = 'All';

        $response = $this->call('GetQueueAttributes', $params, $queue->getUrl());

        $data = array();
        foreach ($response['Attribute'] as $attribute) {
            $key = lcfirst($attribute['Name']);
            $data[$key] = $attribute['Value'];
        }
        
        $data = array_merge($this->getSerializer()->normalize($queue), $data);
        $queue = $this->getSerializer()->denormalize($data, '\AmazonSQS\Model\Queue');

        $this->getQueueStorage()->add($queue);
        
        return $queue;
    }

    /**
     * Perform a apiTalk request and convert the XML response
     * 
     * @param string $action Action name
     * @param array  $params (Optional) Params
     * @param string $url    (Optional) URL
     * 
     * @return mixed
     * 
     * @throws \RuntimeException 
     */
    public function call($action, $params = array(), $url = null)
    {
        if (!$url) {
            $url = $this->getUrl();
        }

        $params['Action'] = $action;

        $response = $this->getClient()->post($url, $params);

        $xmlRoot = simplexml_load_string($response->getContent());
        $data = $this->convertXmlToArray($xmlRoot);
        
        if (isset($data['Error'])) {
            throw new \RuntimeException($data['Error']['Message']);
        }

        if (isset($data[$action . 'Result'])) {
            return $data[$action . 'Result'];
        } else {
            return true;
        }
    }

    /**
     * Convert XML to an array
     * 
     * @param object $xmlObject The xml object
     * @param array  $data      )Optional) Append data array
     * 
     * @return array 
     */
    protected function convertXmlToArray($xmlObject, array $data = array())
    {
        foreach ((array) $xmlObject as $key => $xmlNode) {
            if (is_object($xmlNode) || is_array($xmlNode)) {
                $data[$key] = $this->convertXmlToArray($xmlNode);
            } else {
                $data[$key] = (string) $xmlNode;
            }
        }

        return $data;
    }

}
