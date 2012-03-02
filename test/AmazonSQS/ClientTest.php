<?php

namespace Test\AmazonSQS;

use AmazonSQS\Client;

use apiTalk\Request;
use apiTalk\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    public function testSendGet()
    {
        $mockAdapter = $this->getMockBuilder('apiTalk\Adapter\AdapterInterface')
                            ->setMethods(array('send'))
                            ->getMock();
        
        $mockAdapter->expects($this->once())
                           ->method('send')
                           ->will($this->returnValue(new Response()));
        
        $time = mktime(12, 12, 12, 03, 05, 2012);
        $request = new Request('http://www.test.com/path', Request::METHOD_GET, array('params' => 'some_value'));
        
        $client = new Client('AccessKey', 'SecretKey', $mockAdapter);
        $response = $client->send($request, $time);
        
        $this->assertEquals('AccessKey', $request->getParameter('AWSAccessKeyId'), 'Wrong aws access key');
        $this->assertEquals('2012-03-05T11:12:12Z', $request->getParameter('Expires'), 'Wrong expire date');
        $this->assertEquals('HmacSHA256', $request->getParameter('SignatureMethod'), 'Wrong signature method');
        $this->assertEquals('2', $request->getParameter('SignatureVersion'), 'Wrong signature version');
        $this->assertEquals('2011-10-01', $request->getParameter('Version'), 'Wrong version');
        $this->assertEquals('some_value', $request->getParameter('params'), 'Wrong custom value');
        $this->assertEquals('BRtok0yeMJvMD8Muy+rK1AjQRTKHTL0d6urCPpoqqnc=', $request->getParameter('Signature'), 'Wrong signature');  
    }

    public function testSendPost()
    {
        $mockAdapter = $this->getMockBuilder('apiTalk\Adapter\AdapterInterface')
                            ->setMethods(array('send'))
                            ->getMock();
        
        $mockAdapter->expects($this->once())
                           ->method('send')
                           ->will($this->returnValue(new Response()));
        
        $time = mktime(12, 12, 12, 03, 05, 2012);
        $request = new Request('http://www.test.com/path', Request::METHOD_POST, array('params' => 'some_value', 'Signature' => 'old_signature'));
        
        $client = new Client('AccessKey', 'SecretKey', $mockAdapter);
        $response = $client->send($request, $time);
        
        $this->assertEquals('AccessKey', $request->getParameter('AWSAccessKeyId'), 'Wrong aws access key');
        $this->assertEquals('2012-03-05T11:12:12Z', $request->getParameter('Expires'), 'Wrong expire date');
        $this->assertEquals('HmacSHA256', $request->getParameter('SignatureMethod'), 'Wrong signature method');
        $this->assertEquals('2', $request->getParameter('SignatureVersion'), 'Wrong signature version');
        $this->assertEquals('2011-10-01', $request->getParameter('Version'), 'Wrong version');
        $this->assertEquals('some_value', $request->getParameter('params'), 'Wrong custom value');
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeader('Content-Type'), 'Wrong content-type header');
        $this->assertEquals('2HO/8LLZn3+epdgbbkkXiaZ1itqV0Ql5mxZQwCbZhF4=', $request->getParameter('Signature'), 'Wrong signature');  
    }    
}