<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\CsvEncoder;

class CsvService extends CsvEncoder
{
    public function csvToArray($path)
    {
        return $this->decode(file_get_contents($path), 'csv', [CsvEncoder::DELIMITER_KEY => ';']);
    }
}