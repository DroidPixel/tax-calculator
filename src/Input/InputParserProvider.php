<?php

namespace Paysera\Input;

class InputParser
{
    private $inputParsers;

    public function __construct()
    {
        $this->inputParsers = [];
    }

    public function addParser(string $extension, InputParserInterface $parser)
    {
        $this->inputParsers[$extension] = $parser;

        return $this;
    }

    public function getParserByKey(string $extension)
    {
        return $this->inputParsers[$extension];
    }
}