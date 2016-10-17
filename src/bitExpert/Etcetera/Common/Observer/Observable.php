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

interface Observable
{
    /**
     * Registers given observer
     * @param Observer $observer
     */
    public function registerObserver(Observer $observer);

    /**
     * Unregisters given observer
     * @param Observer $observer
     */
    public function unregisterObserver(Observer $observer);

    /**
     * Notifies all registered observers
     */
    public function notifyObservers();
}
