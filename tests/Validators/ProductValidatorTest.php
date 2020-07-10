<?php

namespace App\Tests\Validators;

use App\Entity\Product;
use App\Validator\Constraint\SpecialPriceConstraint;
use App\Validator\Validator\SpecialPriceValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class ProductValidatorTest
 * @package App\Tests\Validators
 */
class ProductValidatorTest extends TestCase
{
    /**
     * @var SpecialPriceConstraint
     */
    private $constraint;

    /**
     * @var ExecutionContextInterface
     */
    private $context;

    /**
     * @var SpecialPriceValidator|MockObject
     */
    private $specialPriceValidator;

    public function setUp()
    {
        $this->constraint = $this->createMock(SpecialPriceConstraint::class);

        $product = $this->createMock(Product::class);
        $product->method("price")
            ->willReturn(11);

        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->context->method("getRoot")->willReturn($product);

        $this->specialPriceValidator = $this->getMockBuilder(SpecialPriceValidator::class)
            ->setMethods(['getContext'])->getMock();

        $this->specialPriceValidator
            ->expects($this->any())
            ->method("getContext")->willReturn($this->context);

        parent::setUp();
    }

    public function testSpecialPriceValidator(){

        $this->assertTrue($this->specialPriceValidator->validate(10, $this->constraint));

        $this->context->expects($this->once())
            ->method("addViolation")->willReturnCallback(
                function($message) {
                    $this->assertEquals($this->constraint->message, $message);
                }
            );

        $this->assertFalse($this->specialPriceValidator->validate(12, $this->constraint));
    }

    public function testSpecialPriceValidatorException() {
        $wrongConstraint = $this->createMock(Constraint::class);

        $this->expectException(UnexpectedTypeException::class);

        $this->specialPriceValidator->validate(12, $wrongConstraint);
    }

}