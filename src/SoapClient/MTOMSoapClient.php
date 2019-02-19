<?php

namespace Larangular\WebServiceManager\SoapClient;

use SoapClient;
use Exception;

class MTOMSoapClient extends SoapClient
{

    private $lastSoapOutputHeaders;
    /**
     * Override SoapClient to add MTOM decoding on responses.
     *
     * It replaces the :
     *      <xop:Include href="cid:d08bab58-dfea-43f0-8520-477d4c5e0677-103@cxf.apache.org" xmlns:xop="http://www.w3.org/2004/08/xop/include"/>
     * By the binary code contained in attachment
     *      Content-ID: <d08bab58-dfea-43f0-8520-477d4c5e0677-103@cxf.apache.org>
     *
     * Note that the binary in converted to base64 with base64_encode().
     *
     * @link http://php.net/manual/en/soapclient.dorequest.php
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int $version
     * @param int $one_way
     * @return string The XML SOAP response with <xop> tag replaced by base64 corresponding attachment
     * @throws Exception
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $exception = null;
        try {
            $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        }
        catch (SoapFault $sf) {
            //this code was not reached
            $exception = $sf;
        }
        catch (Exception $e) {
            //nor was this code reached either
            $exception = $e;
        }
        if((isset($this->__soap_fault)) && ($this->__soap_fault != null)) {
            //this is where the exception from __doRequest is stored
            $exception = $this->__soap_fault;
        }

        if($exception != null) {
            throw $exception;
        }

        return app('ws-manager.mtom-decode')->decode($response);
        //return (new MTOMDecode())->decode($response);
    }

    public function getLastSoapOutputHeaders(){
        return $this->lastSoapOutputHeaders;
    }

}
