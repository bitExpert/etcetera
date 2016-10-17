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
namespace bitExpert\Etcetera\Common\Logging;

use bitExpert\Slf4PsrLog\LoggerFactory;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Trait {@link \Tmm\Core\Logging\Common\LoggerTrait} provides support for
 * logging
 */
trait LoggerTrait
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @return LoggerInterface
     */
    private function getLogger() : LoggerInterface
    {
        if (!$this->logger) {
            $this->logger = LoggerFactory::getLogger(get_class($this));
        }

        return $this->logger;
    }

    /**
     * @param string $message
     * @param int $severity
     */
    public function log(string $message, $severity = Logger::DEBUG)
    {
        $this->getLogger()->log($severity, $message);
    }

    /**
     * @param $message
     */
    public function logWarning(string $message)
    {
        $this->getLogger()->warning($message);
    }

    /**
     * @param $message
     */
    public function logDebug(string $message)
    {
        $this->getLogger()->debug($message);
    }

    /**
     * @param $message
     */
    public function logInfo(string $message)
    {
        $this->getLogger()->info($message);
    }

    /**
     * @param $message
     */
    public function logError(string $message)
    {
        $this->getLogger()->error($message);
    }
}
