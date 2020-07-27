<?php

namespace App\Transformers;

/**
 * Interface DescriptionConverterInterface

 * @package App\Converters
 */
interface DescriptionConverterInterface
{
    public function convert(string $description): string;
}
