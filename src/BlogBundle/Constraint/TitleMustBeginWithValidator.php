<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BlogBundle\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use BlogBundle\Constraint\TitleMustBeginWith;

class TitleMustBeginWithValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TitleMustBeginWith) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\TitleMustBeginWith');
        }
        if ( $value != "xyz") {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(TitleMustBeginWith::MUST_BEGIN_WITH_ERROR)
                ->addViolation();
        }
    }
}
