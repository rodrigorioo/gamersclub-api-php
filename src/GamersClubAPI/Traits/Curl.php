<?php

namespace GamersClubAPI\Traits;

use GamersClubAPI\Exceptions\Curl\Curl as CurlException;

trait Curl {

    public function getOptions ($url) {

        $finalUrl = $this->getUrl().$url;

        return [
            CURLOPT_URL => $finalUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Cookie: gclubsess=".$this->getSessionId(),
            ],
            // CURLOPT_PROXY => $proxy['ip'],
            // CURLOPT_PROXYPORT => $proxy['puerto'],
            // CURLOPT_PROXYUSERPWD => $proxy['login'],
            // CURLOPT_PROXYTYPE => 'HTTP',
            // CURLOPT_HTTPPROXYTUNNEL => 1,
            // CURLOPT_TIMEOUT => 60,
        ];
    }

    public function execCurl($url) {

        $curl = curl_init();

        curl_setopt_array($curl, $this->getOptions($url));

        $responseHTML = curl_exec($curl);
        $curlError = curl_error($curl);

        if($curlError) {
            throw new CurlException;
        }

        return $responseHTML;
    }
}