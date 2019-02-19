<?php
/**
 * This file is part of the KeepItSimple package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   KeepItSimple\Http\Soap
 * @author    Alexandre Debusschere (debuss-a)
 * @copyright Copyright (c) Alexandre Debusschere <alexandre@debuss-a.com>
 * @licence   MIT
 */

namespace Larangular\WebServiceManager\SoapClient;

use Exception;

/**
 * Class MTOMSoapClient
 *
 * This class overrides SoapClient::__doRequest() method to implement MTOM for PHP.
 * It decodes XML and integrate attachments in the XML response.
 *
 * @author Alexandre D. <debuss-a>
 * @version 1.0.0
 */
class MTOMDecode {

    public function __construct() {}

    public function decode($response){
        $xml_response = null;
        // Catch XML response
        preg_match('/<s[\s\S]*nvelope>/', $response, $xml_response);

        if (!is_array($xml_response) || !count($xml_response)) {
            throw new Exception('No XML has been found.');
        }

        $xml_response = reset($xml_response);

        // Look if xop then replace by base64_encode(binary)
        $xop_elements = null;
        preg_match_all('/<xop[\s\S]*?\/>/', $response, $xop_elements);
        $xop_elements = reset($xop_elements);

        if (is_array($xop_elements) && count($xop_elements)) {
            foreach ($xop_elements as $xop_element) {
                $xml_response = $this->xopReplace($xop_element, $response, $xml_response);
            }
        }

        return $xml_response;
    }

    private function xopReplace($xop_element, $response, $xml_response) {
        $cid = $this->getContentId($xop_element);
        $binary = base64_encode($this->getBinaryContent($cid, $response));
        $binary = base64_encode($binary);
        // Replace xop:Include tag by base64_encode(binary)
        // Note: SoapClient will automatically base64_decode(binary)
        return preg_replace('/<xop:Include[\s\S]*cid:'.preg_quote($cid, '/').'[\s\S]*?\/>/', $binary, $xml_response);
    }

    private function getContentId($element) {
        $cid = null;
        preg_match('/(["\'])cid:(.*?)\1/', $element, $cid);
        return $cid[2];
    }

    private function getBinaryContent($contentId, $response) {
        $binary = null;
        preg_match('/Content-ID:[\s\S].+?'.preg_quote($contentId, '/').'>([\s\S]*?)--uuid/', $response, $binary);

        return trim($binary[1]);
    }

}
