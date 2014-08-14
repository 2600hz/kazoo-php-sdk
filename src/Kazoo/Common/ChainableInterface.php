<?php

namespace Kazoo\Common;

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
