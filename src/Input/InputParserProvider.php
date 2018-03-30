<?php

namespace Paysera\Input;

class InputParserProvider
{
    private $inputParsers;
    private $supportedExtensions = ['csv', 'json'];

    public function __construct()
    {
        $this->inputParsers = [];
    }

    public function addParser(string $extension, InputParserInterface $parser): self
    {
            $this->inputParsers[$extension] = $parser;
            return $this;
    }

    /**
     * @param string $extension
     * @return InputParserInterface
     * @throws \Exception
     */
    public function getParserByKey(string $extension): InputParserInterface
    {
        if (in_array($extension, $this->supportedExtensions)) {
            return $this->inputParsers[$extension];
        }

        throw new \Exception("Extension not supported. \n");
    }

}