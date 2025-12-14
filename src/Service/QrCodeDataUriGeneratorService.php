<?php

/*
 * This file is part of the 2amigos/2fa-library project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Da\TwoFA\Service;

use Da\QrCode\Contracts\ErrorCorrectionLevelInterface;
use Da\QrCode\QrCode;
use Da\TwoFA\Contracts\StringGeneratorServiceInterface;

class QrCodeDataUriGeneratorService implements StringGeneratorServiceInterface
{
    /**
     * QrCodeDataUriGeneratorService constructor.
     *
     * @param string $totpSecretKeyUri a totp secret key uri generated string.
     * @param int    $size             the size of the qr code. Recommended size is 200 for readability.
     */
    public function __construct(
        private string $totpSecretKeyUri,
        private int $size = 200
    ) {
    }

    /**
     * @inheritdoc
     */
    public function run(): string
    {
        return (new QrCode($this->totpSecretKeyUri, ErrorCorrectionLevelInterface::MEDIUM))
            ->setSize((int)$this->size)
            ->writeDataUri();
    }
}
