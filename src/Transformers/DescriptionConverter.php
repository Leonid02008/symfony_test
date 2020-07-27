<?php

namespace App\Transformers;

use voku\helper\AntiXSS;

class DescriptionConverter implements DescriptionConverterInterface
{

    /**
     * @var AntiXSS
     */
    private $antiXSS;

    /**
     * DescriptionConverter constructor.
     * @param AntiXSS $antiXSS
     */
    public function __construct()
    {
        $this->antiXSS = new AntiXSS();//$antiXSS;
    }

    public function convert(string $description): string
    {
        $xssRemoved = $this->antiXSS->xss_clean($description);
        // stripping all after 65565 charset
        $stripped = substr($xssRemoved, 0, 65535);

        return $stripped;
    }
}
