<?php

declare(strict_types=1);

namespace App\Services;

use ClientRequestException;

class XMClient
{

    /**
     * MAke request to an external endpoint
     *
     * @param string $startDate
     * @param string $endDate
     * @param string $symbol
     * @throws ClientRequestException
     * @return string
     */
    public function makeRequest(
        string $url,
        string $method,
        array $headers
    ): string {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new ClientRequestException("cURL Error #:" . $err);
        } else {
            return $response;
        }
    }
}
