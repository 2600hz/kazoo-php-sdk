<?php

namespace Kazoo\Api;

/**
 * Manages Kazoo Auth Tokens.
 *
 */
interface ChainableInterface
{
    function getTokenUri();

    function getSDK();

    function getTokenValues();
}
