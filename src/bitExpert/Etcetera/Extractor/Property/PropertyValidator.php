<?php
declare(strict_types = 1);

/*
 * This file is part of the Etcetera package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bitExpert\Etcetera\Extractor\Property;

use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;

/**
 * Interface PropertyValidator
 */
interface PropertyValidator
{
    /**
     * Validates the given $valueDescriptor
     * for correctness
     *
     * @param ValueDescriptor $valueDescriptor
     * @return bool
     */
    public function validate(ValueDescriptor $valueDescriptor): bool;
}
