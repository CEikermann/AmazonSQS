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

class DeleteMessageBatchResultEntry {

	private $id;
	private $senderFault;	
	private $code;
	private $message;
	
	const MESSAGE_INDEX = 'Message';
	const SENDERFAULT_INDEX = 'SenderFault';
	const CODE_INDEX = 'Code';
	const ID_INDEX = 'Id';

	public function __construct($data = NULL) {
		if ( is_array($data) ) {
			$this->fromArray($data);
		}
	}

	public function fromArray($array) {
		$this->setId($array[DeleteMessageBatchResultEntry::ID_INDEX]);
		
		if ( array_key_exists(DeleteMessageBatchResultEntry::MESSAGE_INDEX, $array) ) 
		{
			$this->setMessage($array[DeleteMessageBatchResultEntry::MESSAGE_INDEX]);
		}
		
		if ( array_key_exists(DeleteMessageBatchResultEntry::SENDERFAULT_INDEX, $array) ) 
		{		
			$this->setSenderFault($array[DeleteMessageBatchResultEntry::SENDERFAULT_INDEX]);
		}

		if ( array_key_exists(DeleteMessageBatchResultEntry::CODE_INDEX, $array) ) 
		{
			$this->setCode($array[DeleteMessageBatchResultEntry::CODE_INDEX]);
		}
	}

	public function getId() 
	{
		return $this->id;
	}

	public function setId($id) 
	{
		$this->id = $id;
	}

	public function getSenderFault() 
	{
		return $this->senderFault;
	}

	public function setSenderFault($senderFault) 
	{
		$this->senderFault = $senderFault;
	}

	public function getCode() 
	{
		return $this->code;
	}

	public function setCode($code) 
	{
		$this->code = $code;
	}

	public function getMessage() 
	{
		return $this->message;
	}

	public function setMessage($message) 
	{
		$this->message = $message;
	}

	public function isDeletionFailure() 
	{
		return !is_null($this->getSenderFault()) || !is_null($this->getCode()) || !is_null($this->getMessage());
	}
}

?>
