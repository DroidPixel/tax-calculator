<?php

namespace Paysera\Input;


interface InputParserInterface
{
    public function parseFromFile($fileName);
}