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
namespace bitExpert\Etcetera\Reader;

use bitExpert\Etcetera\Common\Observer\Observable;
use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;

/**
 * Interface IReader
 */
interface Reader extends Observable
{
    /**
     * @return mixed
     */
    public function read();

    /**
     * Returns the current value descriptors
     *
     * @return ValueDescriptor[]
     */
    public function getValues();

    /**
     * Returns meta data descriptor of the used source
     * @return mixed
     */
    public function getMeta();
}
