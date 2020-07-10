<?php

namespace App\Validator\Constraint;

use App\Validator\Validator\SpecialPriceValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class SpecialPriceConstraint
 *
 * @package App\Validator\Constraint
 */
class SpecialPriceConstraint extends Constraint
{
    public $message = 'The special price is not less than the price.';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return SpecialPriceValidator::class;
    }
}
