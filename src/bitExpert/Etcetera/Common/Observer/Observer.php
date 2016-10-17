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
namespace bitExpert\Etcetera\Common\Observer;

interface Observer
{
    /**
     * Function which is called by the {@link bitExpert\Etcetera\Common\Observer\IObservable}
     * to which we the observer is registered to
     */
    public function update();
}
