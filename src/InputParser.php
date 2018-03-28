<?php

namespace Paysera;

class InputParser
{
    private $extension;
    private $parser;

    public function addParser(string $extension, FileParser $parser)
    {
        $this->extension = $extension;
        $this->parser = $parser;
    }
}