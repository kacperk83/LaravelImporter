<?php

namespace App\Listeners\Import;

use JsonStreamingParser\Listener;
use Closure;

/**
 *
 * @author Kacper Kowalski kacperk83@gmail.com
 *
 */
class JsonImportListener implements Listener
{
    /**
     * @var int $level Nesting level in each (User) document
     */
    private $level = 0;

    /**
     * @var array $stack Collects a full document (ie. A user+creditcard)
     */
    private $stack = [];

    /**
     * @var string $currentKey
     */
    private $currentKey;

    /**
     * @var Closure $callback The function to execute when we have a full document
     */
    private $callback;

    /**
     * JsonImportListener constructor.
     *
     * @param $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    public function startDocument()
    {
        echo 'start document';
    }

    public function endDocument()
    {
        echo 'end document';
    }

    public function startObject()
    {
        $this->level++;

        echo 'start object';
    }

    public function endObject()
    {
        $this->level--;

        if ($this->level == 0) {
            call_user_func($this->callback, $this->stack);
            $this->stack = [];
        }

        echo 'end object';
    }

    public function startArray()
    {
        echo 'start array';

    }

    public function endArray()
    {
        echo 'start array';
    }

    public function key($key)
    {
        $this->currentKey = $key;

        echo 'key:' . $key;

    }

    public function value($value)
    {
        if ($this->level == 2) {
            $this->stack['credit_card'][$this->currentKey] = $value;
        } else {
            $this->stack[$this->currentKey] = $value;
        }
        echo 'value: ' . $value;

    }

    public function whitespace($whitespace)
    {
        echo 'whitespace: ' . $whitespace;
    }
}
