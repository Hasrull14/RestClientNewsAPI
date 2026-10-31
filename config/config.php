
<?php

define("NEWS_API_KEY", "11ebcc6f54d346a385b083fb9cc35d91");

function http_request_get($url)
{
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => "NewsHub/1.0",
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_HTTPHEADER => [
            "Accept: application/json"
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    curl_close($ch);

    if ($response === false) {
        return [
            "success" => false,
            "error" => "Connection error: " . $curlError
        ];
    }

    $data = json_decode($response, true);

    if (!is_array($data)) {
        return [
            "success" => false,
            "error" => "Invalid API response."
        ];
    }

    if ($httpCode !== 200 || ($data["status"] ?? "") !== "ok") {
        return [
            "success" => false,
            "error" => $data["message"] ?? "API request failed."
        ];
    }

    return [
        "success" => true,
        "data" => $data
    ];
}

function e($value)
{
    return htmlspecialchars(
        (string) ($value ?? ""),
        ENT_QUOTES,
        "UTF-8"
    );
}

