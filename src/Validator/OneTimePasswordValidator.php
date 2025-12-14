<?php

/*
 * This file is part of the 2amigos/2fa-library project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\TwoFA\Validator;

use Da\TwoFA\Contracts\ValidatorInterface;
use Da\TwoFA\Traits\OathTrait;

class OneTimePasswordValidator implements ValidatorInterface
{
    use OathTrait;


    /**
     * OneTimePasswordValidator constructor.
     *
     * @param string   $seed
     * @param int      $cycles
     * @param int      $tokenLength
     * @param int      $startTime
     * @param int      $time
     * @param int|null $previousTime
     */
    public function __construct(
        protected string $seed,
        protected int $cycles,
        int $tokenLength,
        protected int $startTime,
        protected int $time,
        protected ?int $previousTime = null
    ) {
        $this->tokenLength = $tokenLength;
    }

    /**
     * Validates OTP. If `$previousTime` has been added, it will return the `$startTime`, otherwise it will return a
     * bool value. This is done to prevent an attacker to use the same key again.
     *
     * @param mixed $value
     *
     * @return bool|int
     */
    public function validate($value): bool|int
    {
        for (; $this->startTime <= $this->time + $this->cycles; $this->startTime++) {
            if (hash_equals($this->oathHotp($this->seed, $this->startTime), $value)) {
                return
                    null === $this->previousTime
                        ? true
                        : $this->startTime;
            }
        }

        return false;
    }
}
