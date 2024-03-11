<?php

namespace Monobill\MonobillPhpSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;

class MonoBillAPI
{
    private $apiURL = 'https://api.gomonobill.com/';

    private $clientId;
    private $clientSecret;
    private $scopes;
    private $queryParams;
    private $callbackUrl;
    private $token;
    private $storeId;

    public function __construct(string $clientId, string $clientSecret, string $token = null, string $storeId = null, $endpoint = 'app')
    {
        $this->apiURL .= $endpoint;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->queryParams = $_GET;
        $this->token = $token;
        $this->storeId = $storeId;
    }

    public function getInstallURL()
    {
        // Check if 'store_domain' is provided in query parameters
        if (!isset($this->queryParams['store_domain'])) {
            die('Provide store_domain');
        }
        return 'https://' . $this->queryParams['store_domain'] . '/admin/oauth/authorize?client_id=' . $this->clientId . '&redirect=' . $this->callbackUrl . '&scopes=' . implode(',', $this->scopes);
    }

    public function validateInstallRequest()
    {
        // Validate install request parameters using HMAC
        if (!isset($this->queryParams['app_id']) || !isset($this->queryParams['access_token']) || !isset($this->queryParams['store']) || !isset($this->queryParams['store_id']) || !isset($this->queryParams['hmac'])) {
            return false;
        }

        $computed_hmac = hash_hmac('sha256', $this->queryParams['app_id'] . ',' . $this->queryParams['access_token'] . ',' . $this->queryParams['store'] . ',' . $this->queryParams['store_id'] . ((isset($this->queryParams['plan'])) ? ',' . $this->queryParams['plan'] : ''),  $this->clientSecret);

        return hash_equals($this->queryParams['hmac'], $computed_hmac);
    }

    public function verifyRequest()
    {
        // Verify request parameters using HMAC
        if (!isset($this->queryParams['timestamp']) || !isset($this->queryParams['store']) || !isset($this->queryParams['store_id']) || !isset($this->queryParams['hmac']) || !isset($this->queryParams['app_id'])) {
            return false;
        }

        $computed_hmac = hash_hmac('sha256', $this->queryParams['app_id'] . ',' . $this->queryParams['store'] . ',' . $this->queryParams['store_id'] . ((isset($this->queryParams['plan'])) ? ',' . $this->queryParams['plan'] : ''), $this->clientSecret);

        return hash_equals($this->queryParams['hmac'], $computed_hmac);
    }

    public function setCallbackUrl($url)
    {
        // Set the callback URL
        $this->callbackUrl = $url;
    }

    public function setScopes(array $scopes)
    {
        // Set the scopes
        $this->scopes = $scopes;
    }

    // Methods for HTTP requests

    /**
     * @param string $action
     * @param array $headers
     * @param bool $throw
     * @return mixed
     * @throws GuzzleException
     */
    public function get(string $action, array $headers = [], bool $throw = true): mixed
    {
        // Perform a GET request
        try {
            return $this->request($this->apiURL, 'GET', $action, [], $headers);
        } catch (GuzzleException $e) {
            if ($throw) {
                throw $e;
            } else {
                return null;
            }
        }
    }

    /**
     * @param string $action
     * @param array $data
     * @param array $headers
     * @param bool $throw
     * @return mixed
     */
    public function post(string $action, array $data, array $headers = [], bool $throw = true): mixed
    {
        // Perform a GET request
        try {
            return $this->request($this->apiURL, 'POST', $action, $data, $headers);
        } catch (GuzzleException $e) {
            if ($throw) {
                throw $e;
            } else {
                return null;
            }
        }
    }

    /**
     * @param string $action
     * @param array $data
     * @param array $headers
     * @param bool $throw
     * @return mixed
     */
    public function put(string $action, array $data, array $headers = [], bool $throw = true): mixed
    {
        // Perform a GET request
        try {
            return $this->request($this->apiURL, 'PUT', $action, $data, $headers);
        } catch (GuzzleException $e) {
            if ($throw) {
                throw $e;
            } else {
                return null;
            }
        }
    }

    /**
     * @param string $action
     * @param array $data
     * @param array $headers
     * @param bool $throw
     * @return mixed
     */
    public function patch(string $action, array $data, array $headers = [], bool $throw = true): mixed
    {
        // Perform a GET request
        try {
            return $this->request($this->apiURL, 'PATCH', $action, $data, $headers);
        } catch (GuzzleException $e) {
            if ($throw) {
                throw $e;
            } else {
                return null;
            }
        }
    }

    /**
     * @param string $action
     * @param array $headers
     * @param bool $throw
     * @return mixed
     */
    public function delete(string $action, array $headers = [], bool $throw = true): mixed
    {
        // Perform a GET request
        try {
            return $this->request($this->apiURL, 'DELETE', $action, [], $headers);
        } catch (GuzzleException $e) {
            if ($throw) {
                throw $e;
            } else {
                return null;
            }
        }
    }

    /**
     * @param string $apiUrl
     * @param string $method
     * @param string $action
     * @param array $data
     * @param array $headers
     * @return mixed
     * @throws GuzzleException
     */
    public function request(string $apiUrl, string $method, string $action, array $data = [], array $headers = []): mixed
    {
        // Create a Guzzle HTTP client and perform the request

        $action = '/' . ltrim($action, '/');

        $allHeaders = [];
        $getQuery = '';

        // Remove specific headers if they are empty
        if (isset($allHeaders['Content-Length']) && empty($allHeaders['Content-Length'])) {
            unset($allHeaders['Content-Length']);
        }
        if (isset($allHeaders['Content-Type']) && empty($allHeaders['Content-Type'])) {
            unset($allHeaders['Content-Type']);
        }

        // Set up Guzzle HTTP client
        $http = new Client([
            'headers' => array_merge($allHeaders, [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
                'X-CLIENT-REAL-IP' => $this->getIPAddress(),
                'MB-APP-ID' => $this->clientId,
                'MB-STORE-ID' => $this->storeId
            ], $headers)
        ]);

        $options = [];

        $files = request()->allFiles();

        // Handle file uploads
        if (count($files)) {
            // Multipart request for file uploads
            $options['multipart'] = [];
            foreach ($files as $key => $file) {
                $options['multipart'][] = [
                    'name' => $key,
                    'contents' => $file->getContent(),
                    'filename' => $file->getClientOriginalName(),
                ];
            }
            foreach (request()->input() as $key => $value) {
                $options['multipart'][] = [
                    'name' => $key,
                    'contents' => $value
                ];
            }
        } else {
            // Handle other types of requests
            if (strtolower($method) !== 'get') {
                $options['json'] = $data;
            } else {
                if (count($data)) {
                    $getQuery = '?' . http_build_query($data);
                }
            }
        }

        // Perform the HTTP request
        try {
            $response = $http->request($method, $apiUrl . $action . $getQuery, $options);
            if ($body = $response->getBody()) {
                if ($response->getHeader('Content-Type')[0] === 'application/json') {
                    return json_decode($body, true);
                } else {
                    return $body->getContents();
                }
            } else {
                return null;
            }
        } catch (BadResponseException $e) {
            // Handle bad response exceptions
            throw $e;
        }
    }

    private function getIPAddress()
    {
        // Get the real client IP address from various HTTP headers
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        if (isset($_SERVER["HTTP_X_REAL_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_REAL_IP"];
        }
        if (isset($_SERVER["HTTP_X_CLIENT_REAL_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_X_CLIENT_REAL_IP"];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}
