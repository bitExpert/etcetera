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
namespace bitExpert\Etcetera\Extractor\Config;

use PhpOption\Option;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlConfigReader
 */
class YamlConfigReader extends AbstractConfigReader
{
    /**
     * {@inheritDoc}
     */
    public function read(string $filename) : Option
    {
        if (!\file_exists($filename)) {
            throw new \InvalidArgumentException(\sprintf('Could not load config file "%s"', $filename));
        }

        $config = Yaml::parse(file_get_contents($filename));

        return $this->fromArray($config);
    }
}
