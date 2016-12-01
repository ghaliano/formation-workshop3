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

/**
 * @Annotation
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class TitleMustBeginWith extends Constraint
{
    const MUST_BEGIN_WITH_ERROR = 'c1051bb4-d103-4f74-8988-acbcafc7fdc355';

    protected static $errorNames = array(
        self::MUST_BEGIN_WITH_ERROR => 'MUST_BEGIN_WITH_ERROR',
    );

    public $message = 'This value "{{ value }}" must begin with';
}
