<?php

/**
 * This file contains QUI\Smarty\Collector
 */

namespace QUI\Smarty;

/**
 * Class Collector
 * HTML Smarty Collector for {templateEvent}
 *
 * @package QUI\Smarty
 */
class Collector
{
    /**
     * Internal Content
     *
     * @var string
     */
    protected $content = '';

    /**
     * @param string $content
     */
    public function append($content)
    {
        $this->content .= $content;
    }

    /**
     * Return the collected content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
