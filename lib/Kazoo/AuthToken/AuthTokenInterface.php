<?php

namespace Kazoo\AuthToken;

/**
 * Manages Kazoo Auth Tokens.
 *
 */
interface AuthTokenInterface
{
    /**
     * Set the Kazoo Client using the client
     *
     *
     * @param \Kazoo\Client
     */
    public function setClient(\Kazoo\Client $client);

    /**
     * Get the auth-token
     *
     *
     * @return null|string
     */
    public function getToken();

    /**
     * Get the account id associated with the auth-token
     *
     *
     * @return null|string
     */
    public function getAccountId();

    /**
     * Removes the current auth token
     *
     *
     *
     */
    public function reset();
}