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
namespace bitExpert\Etcetera\Extractor;

use bitExpert\Etcetera\Extractor\Exception\ExtractExclusionException;
use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;
use bitExpert\Etcetera\Extractor\Value\Candidate;

/**
 * Class ValueExtractor
 * @package bitExpert\Etcetera\Extractor
 */
class ValueExtractor
{
    /**
     * @var Candidate[]
     */
    private $candidates;

    /**
     * @param Candidate[] $candidates
     */
    public function __construct(array $candidates)
    {
        $this->candidates = [];
        foreach ($candidates as $candidate) {
            $this->addCandidate($candidate);
        }
    }

    /**
     * @param Candidate $candidate
     */
    public function addCandidate(Candidate $candidate)
    {
        $this->candidates[] = $candidate;
    }

    /**
     * Extracts values
     * @param ValueDescriptor[] $values
     * @return ValueDescriptor
     * @throws ExtractExclusionException
     */
    public function extract(array $values): ValueDescriptor
    {
        $value = null;
        $propertiesNotFound = [];

        // check if value references exists
        foreach ($this->candidates as $candidate) {
            $property = $candidate->getProperty();
            if (!array_key_exists($property, $values)) {
                $propertiesNotFound[] = $property;
                continue;
            }
            /** @var ValueDescriptor $valueDescriptor */
            foreach ($values[$property] as $valueDescriptor) {
                if ($candidate->matches($valueDescriptor->getProperty())) {
                    $value = $valueDescriptor;
                    break;
                }
            }
        }

        if (is_null($value)) {
            throw new ExtractExclusionException('Value could not be determined');
        }

        return $value;
    }
}
