<?php

namespace AmazonSQS;

use apiTalk\Client as BaseClient;
use apiTalk\Request;
use apiTalk\Adapter\AdapterInterface;

/**
 * AmazonSQS apiTalk client for signed requets
 *
 * @author Christian Eikermann <christian@chrisdev.de>
 */
class Client extends BaseClient
{

    /**
     * AWS Access Key
     * 
     * @var string
     */
    private $awsAccessKey = null;
    
    /**
     * AWS Secret Key
     * 
     * @var string
     */
    private $awsSecretKey = null;

    /**
     * Constructor
     * 
     * @param string           $awsAccessKey
     * @param string           $awsSecretKey
     * @param AdapterInterface $adapter 
     */
    function __construct($awsAccessKey, $awsSecretKey, AdapterInterface $adapter = null)
    {
        $this->awsAccessKey = $awsAccessKey;
        $this->awsSecretKey = $awsSecretKey;

        parent::__construct($adapter);
    }

    /**
     * Send a raw HTTP request
     * 
     * @param \apiTalk\Request $request The request object
     * 
     * @return \apiTalk\Response The response object  
     */
    public function send(Request $request, $expires = null)
    {
        $request = $this->signRequest($request, $expires);

        return parent::send($request);
    }

    /**
     * Computes the RFC 2104-compliant HMAC signature for request parameters
     *
     * This implements the Amazon Web Services signature, as per the following
     * specification:
     *
     * 1. Sort all request parameters (including <tt>SignatureVersion</tt> and
     * excluding <tt>Signature</tt>, the value of which is being created),
     * ignoring case.
     *
     * 2. Iterate over the sorted list and append the parameter name (in its
     * original case) and then its value. Do not URL-encode the parameter
     * values before constructing this string. Do not use any separator
     * characters when appending strings.
     *
     * @param \apiTalk\Request $request A request object
     * 
     * @return \apiTalk\Request
     */
    protected function signRequest(Request $request, $expires = null)
    {
        if (!$expires) {
            $expires = time() + 5;
        }
        
        $request->setParameter('AWSAccessKeyId', $this->awsAccessKey);
        $request->setParameter('Version', '2011-10-01');
        $request->setParameter('Expires', gmdate('Y-m-d\TH:i:s\Z', $expires));
        $request->setParameter('SignatureMethod', 'HmacSHA256');
        $request->setParameter('SignatureVersion', '2');
        
        if ($request->getMethod() == Request::METHOD_POST) {
            $request->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        }
        
        // Remove old signature
        $parameters = $request->getParameters();
        if (isset($parameters['Signature'])) {
            unset($parameters['Signature']);
        }

        // Sort params by key 
        uksort($parameters, 'strcmp');
        $request->setParameters($parameters);
        
        // Parse URL
        $url = $request->getUri();
        $path = parse_url($url, PHP_URL_PATH);
        $host = parse_url($url, PHP_URL_HOST);
        
        // Generate raw request        
        $data = strtoupper($request->getMethod())."\n";
        $data .= strtolower($host)."\n";
        $data .= (!empty($path) ? $path : '/')."\n";
        $data .= http_build_query($request->getParameters());
        
        // Build the signature
        $hmac = hash_hmac('sha256', $data, $this->awsSecretKey, true);
        $request->setParameter('Signature', base64_encode($hmac));

        return $request;
    }
    
}
