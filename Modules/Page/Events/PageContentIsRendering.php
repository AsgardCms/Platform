<?php

namespace Modules\Page\Events;

class PageContentIsRendering
{
    /**
     * @var string The body of the page to render
     */
    private $body;
    private $original;

    public function __construct($body)
    {
        $this->body = $body;
        $this->original = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getOriginal()
    {
        return $this->original;
    }

    public function __toString()
    {
        return $this->getBody();
    }
}
