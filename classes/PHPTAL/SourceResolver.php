<?php

/** 
 * @package PHPTAL
 */
interface PHPTAL_SourceResolver 
{
    /**
     * Returns PHPTAL_Source or null.
     */
    public function resolve($path);
}
