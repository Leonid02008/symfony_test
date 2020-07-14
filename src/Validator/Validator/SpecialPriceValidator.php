<?php

namespace App\Validator\Validator;

use App\Validator\Constraint\SpecialPriceConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class SpecialPriceValidator
 *
 * @package App\Validator\Validator
 */
class SpecialPriceValidator extends ConstraintValidator
{
    /**
     * @param mixed $specialPrice
     * @param Constraint $constraint
     *
     * @return bool|void
     */
    public function validate($specialPrice, Constraint $constraint)
    {
        /* @var $constraint SpecialPriceConstraint */
        if (!$constraint instanceof SpecialPriceConstraint) {
            throw new UnexpectedTypeException($constraint, SpecialPriceConstraint::class);
        }

        if (null === $specialPrice) {
            return;
        }

        $price = $this->getContext()->getRoot()->price();

        if ($specialPrice >= $price) {
            $this->getContext()->addViolation(
                $constraint->message
            );
            return false;
        }

        return true;
    }

    /**
     * @return \Symfony\Component\Validator\Context\ExecutionContextInterface
     */
    protected function getContext()
    {
        return $this->context;
    }
}
