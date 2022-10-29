<?php

namespace Mode19\Mello;

class Stack
{
    public int $top;
    public array $stack = array();

    function __construct()
    {
        $this->top = -1;
    }

    // create a function to check whether
    // the stack is empty or not
    public function isEmpty() : bool
    {
        if ($this->top == -1) {
            return true;
        } else {
            return false;
        }
    }

    //create a function to return size of the stack
    public function size() : integer
    {
        return $this->top + 1;
    }

    //create a function to add new element
    public function push($x)
    {
        $this->stack[++$this->top] = $x;
    }

    //create a function to delete top element
    public function pop()
    {
        if ($this->top < 0) {
            return;
        } else {
            return $this->stack[$this->top--];
        }
    }

    public function topElement()
    {
        if ($this->top < 0) {
            return;
        } else {
            return $this->stack[$this->top];
        }
    }
}