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

class DeleteMessageBatchResult {

	protected $entries = array();
	protected $containsFailures = FALSE;

	public function __construct($apiCallResponse = NULL) 
	{
		if ( is_array($apiCallResponse) ) {
			$data = $apiCallResponse;
			if ( array_key_exists('DeleteMessageBatchResultEntry',$data) ) {
				$data = $data['DeleteMessageBatchResultEntry'];
			}

			if ( is_array($data) ) {		
				foreach ( $data as $rawEntry ) 
				{				
					$entry = new DeleteMessageBatchResultEntry($rawEntry);				
					$this->addResultEntry($entry);
				}
			}
		}
	}

	public function getNumberOfEntries() 
	{
		return count($this->entries);
	}

	public function containsFailedEntries() 
	{
		return $this->containsFailures;
	}

	public function succeeded() {
		return !$this->containsFailedEntries() && $this->getNumberOfEntries() > 0;
	}

	public function getFailedEntries() 
	{
		return array_filter($this->entries, function($entry) {
			return $entry->isDeletionFailure() ? $entry : NULL;
		});
	}

	private function addResultEntry(DeleteMessageBatchResultEntry $entry) 
	{
		if ( $entry->isDeletionFailure() ) 
		{
			$this->containsFailures = TRUE;
		}

		$this->entries[] = $entry;
	}
}