<?php
// Data: array("param" => "value") ==> index.php?param=value

/**
 * This function calls the specified API using the data provided via parameters.
 * @param $method String get, post
 * @param $url String the API URI
 * @param $data array|null data for call or nothing at all
 * @param $headers array headers that may include data type and access token
 * @param $extraOpts array|null additional options or null
 * @return bool|string
 */
function apiCall(String $method, String $url, array $data, array $headers, array $extraOpts = null) {
    $curl = curl_init();

    if($method === 'GET' && $data) {
        $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    $optionsArr = [
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => $headers
    ];

    if($extraOpts) {
        $optionsArr = array_merge($optionsArr, $extraOpts);
    }

    if($method === 'POST' && $data) {
        $optionsArr[CURLOPT_POSTFIELDS] = $data;
    }

    curl_setopt_array($curl, $optionsArr);
    $result = curl_exec($curl);
    curl_close($curl);

    return $result;
}
