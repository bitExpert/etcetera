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
namespace bitExpert\Etcetera\Extractor\Extract;

class PropertyExtract
{
    private $name;
    private $value;
    private $isKey;
    private $isPersistent;

    /**
     * PropertyExtract constructor.
     * @param string $name
     * @param $value
     * @param bool $isKey
     * @param bool $isPersistent
     */
    public function __construct(
        string $name,
        $value,
        bool $isKey = false,
        bool $isPersistent = true
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->isKey = $isKey;
        $this->isPersistent = $isPersistent;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function isKey(): bool
    {
        return $this->isKey;
    }

    /**
     * @return bool
     */
    public function isPersistent(): bool
    {
        return $this->isPersistent;
    }
}
