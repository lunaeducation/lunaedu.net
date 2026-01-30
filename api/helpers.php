<?php
/*function login($username, $password, $link) {
    $ch = curl_init();
    $link = rtrim($link, '/');
    $loginUrl = $link . '/HomeAccess/Account/LogOn';
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $loginUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => '/tmp/cookies.txt',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36'
    ]);
    
    $response = curl_exec($ch);
    preg_match('/name="__RequestVerificationToken".*?value="(.*?)"/', $response, $matches);
    $token = $matches[1] ?? '';
    
    $postData = [
        '__RequestVerificationToken' => $token,
        'SCKTY00328510CustomEnabled' => 'True',
        'SCKTY00436568CustomEnabled' => 'True',
        'Database' => '10',
        'VerificationOption' => 'UsernamePassword',
        'LogOnDetails.UserName' => $username,
        'tempUN' => '',
        'tempPW' => '',
        'LogOnDetails.Password' => $password
    ];
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $loginUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($postData),
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    $response = curl_exec($ch);
    
    if (strpos($response, 'Login unsuccessful') !== false) {
        curl_close($ch);
        return false;
    }
    
    return $ch;
}

function makeAuthenticatedRequest($url, $ch) {
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => false,
        CURLOPT_POSTFIELDS => null,
        CURLOPT_RETURNTRANSFER => true
    ]);
    return curl_exec($ch);
}*/

/*function login($username, $password, $link) {
    $ch = curl_init();
    $link = rtrim($link, '/');
    $loginUrl = $link . '/HomeAccess/Account/LogOn';
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $loginUrl,
        CURLOPT_RETURNTRANSFER => true,
        // I don't know what I was on when I implemented this.
        CURLOPT_COOKIEJAR => '/tmp/cookies.txt',
        CURLOPT_COOKIEFILE => '/tmp/cookies.txt',
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36',
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    $response = curl_exec($ch);
    preg_match('/name="__RequestVerificationToken".*?value="(.*?)"/', $response, $matches);
    $token = $matches[1] ?? '';
    
    $postData = [
        '__RequestVerificationToken' => $token,
        'SCKTY00328510CustomEnabled' => 'True',
        'SCKTY00436568CustomEnabled' => 'True',
        'Database' => '10',
        'VerificationOption' => 'UsernamePassword',
        'LogOnDetails.UserName' => $username,
        'tempUN' => '',
        'tempPW' => '',
        'LogOnDetails.Password' => $password
    ];
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $loginUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($postData),
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    $response = curl_exec($ch);
    
    if (strpos($response, 'Login unsuccessful') !== false) {
        curl_close($ch);
        return false;
    }
    
    return $ch;
}*/

function login($username, $password, $link, $providedCookies = null) {
    $cookieFile = sys_get_temp_dir() . '/hac_cookies_' . md5($username . $link . session_id()) . '.txt';
    
    if ($providedCookies !== null && !empty($providedCookies)) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36',
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        file_put_contents($cookieFile, $providedCookies);
        
        $testUrl = $link . '/HomeAccess/Content/Student/Registration.aspx';
        curl_setopt($ch, CURLOPT_URL, $testUrl);
        $testResponse = curl_exec($ch);
        
        if ($testResponse && strpos($testResponse, 'LogOn') === false) {
            return $ch;
        }
        
        curl_close($ch);
    }
    
    $ch = curl_init();
    $link = rtrim($link, '/');
    $loginUrl = $link . '/HomeAccess/Account/LogOn';
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $loginUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => $cookieFile,
        CURLOPT_COOKIEFILE => $cookieFile,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36',
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    $response = curl_exec($ch);
    preg_match('/name="__RequestVerificationToken".*?value="(.*?)"/', $response, $matches);
    $token = $matches[1] ?? '';
    
    $postData = [
        '__RequestVerificationToken' => $token,
        'SCKTY00328510CustomEnabled' => 'True',
        'SCKTY00436568CustomEnabled' => 'True',
        'Database' => '10',
        'VerificationOption' => 'UsernamePassword',
        'LogOnDetails.UserName' => $username,
        'tempUN' => '',
        'tempPW' => '',
        'LogOnDetails.Password' => $password
    ];
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $loginUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($postData),
        CURLOPT_FOLLOWLOCATION => true
    ]);
    
    $response = curl_exec($ch);
    
    if (strpos($response, 'Login unsuccessful') !== false) {
        curl_close($ch);
        return false;
    }
    
    return $ch;
}

function getCookiesFromHandle($ch, $cookieFile) {
    if (file_exists($cookieFile)) {
        return file_get_contents($cookieFile);
    }
    return null;
}

function makeAuthenticatedRequest($url, $ch, $postData = null) {
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36'
    ];
    
    if ($postData) {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($postData);
    }
    
    curl_setopt_array($ch, $options);
    return curl_exec($ch);
}

?>