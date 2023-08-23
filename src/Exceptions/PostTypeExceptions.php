<?php

namespace WDM\PluginComponents\Exceptions;

use Exception;

class PostTypeExceptions extends Exception
{
    public static function postSlugToLong($postTypeName): self
    {
        return new self('Post type slug "' . $postTypeName . '" is ' . strlen($postTypeName)-20 . ' characters too long.' );
    }
}
