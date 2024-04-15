<?php
// Data: array("param" => "value") ==> index.php?param=value

/**
 * This function calls the specified API using the data provided via parameters.
 * @param $method String get, post
 * @param $url String the API URI
 * @param $data array|null data for call or nothing at all
 * @param $headers array headers that may include data type and access token
 * @return bool|string
 */
function apiCall($method, $url, $data, $headers) {
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

    if($method === 'POST' && $data) {
        $optionsArr[CURLOPT_POSTFIELDS] = $data;
    }

    curl_setopt_array($curl, $optionsArr);
    $result = curl_exec($curl);
    curl_close($curl);

    return $result;
}

//TODO: in work - need to fine tune. type checking always a must.
/**
 * Returns a nice version of object that can be printed in a readable manner.
 * @param $arr array object to be displayed
 * @return string
 */
function prettyPrint($arr) {
    $retStr = '<ul>';
    if (is_array($arr)){
        foreach ($arr as $key=>$val){
            if (is_array($val)){
                $retStr .= '<li>' . $key . ' => ' . prettyPrint($val) . '</li>';
            }else{
                $retStr .= '<li>' . $key . ' => ' . $val . '</li>';
            }
        }
    }
    $retStr .= '</ul>';
    return $retStr;
}