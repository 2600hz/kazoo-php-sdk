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
     * @param mixed  $content    Request content
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @return \Kazoo\HttpClient\Message\Response
     */
    public function post($path, $content = null, array $headers = array());

    /**
     * Send a PATCH request
     *
     * @param string $path       Request path
     * @param mixed  $content    Request content
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @internal param array $parameters Request body
     * @return \Kazoo\HttpClient\Message\Response
     */
    public function patch($path, $content = null, array $headers = array());

    /**
     * Send a PUT request
     *
     * @param string $path       Request path
     * @param mixed  $content    Request content
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @return \Kazoo\HttpClient\Message\Response
     */
    public function put($path, $content, array $headers = array());

    /**
     * Send a DELETE request
     *
     * @param string $path       Request path
     * @param mixed  $content    Request content
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @return \Kazoo\HttpClient\Message\Response
     */
    public function delete($path, $content = null, array $headers = array());
}
