<?php
declare(strict_types=1);

/*
 * This file is part of the Etcetera package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bitExpert\Etcetera\Common\Factory;

abstract class AbstractTypeFactory
{
    protected $available;
    protected $instances;

    /**
     * @param array $available
     */
    public function __construct(array $available)
    {
        $this->available = $available;
        $this->instances = [];
    }

    protected function getInstanceForType(string $type)
    {
        $instance = $this->getAvailableByType($type);

        if (is_string($instance)) {
            $instance = new $instance();
        }

        return $instance;
    }

    protected function getAvailableByType(string $type)
    {
        if (!isset($this->available[$type])) {
            throw new \InvalidArgumentException(sprintf(
                'Type %s could not be found. Please check your configuration.',
                $type
            ));
        }

        return $this->available[$type];
    }
}
