<?php

namespace App\Interfaces\csv;

use SplFileInfo;

interface CsvManagerInterface
{
    public function import(SplFileInfo $file): ImportResultInterface;

}