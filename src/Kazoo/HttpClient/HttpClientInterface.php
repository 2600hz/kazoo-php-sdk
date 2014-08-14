<?php

namespace Kazoo\HttpClient;

/**
 * Performs requests on Kazoo API.
 *
 */
interface HttpClientInterface
{
    /**
     * Set HTTP headers
     *
     * @param array $headers
     */
    public function setHeaders(array $headers);

    /**
     * Reset HTTP headers to the SDK options
     *
     *
     */
    public function resetHeaders();

    /**
     * Send a GET request
     *
     * @param string $path       Request path
     * @param array  $parameters GET Parameters
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @return array Data
     */
    public function get($path, array $parameters = array(), array $headers = array());

    /**
     * Send a POST request
     *
     * @param string $path       Request path
     * @param mixed  $body       Request body
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @return array Data
     */
    public function post($path, $body = null, array $headers = array());

    /**
     * Send a PATCH request
     *
     * @param string $path       Request path
     * @param mixed  $body       Request body
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @internal param array $parameters Request body
     * @return array Data
     */
    public function patch($path, $body = null, array $headers = array());

    /**
     * Send a PUT request
     *
     * @param string $path       Request path
     * @param mixed  $body       Request body
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @return array Data
     */
    public function put($path, $body, array $headers = array());

    /**
     * Send a DELETE request
     *
     * @param string $path       Request path
     * @param mixed  $body       Request body
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @return array Data
     */
    public function delete($path, $body = null, array $headers = array());
}
