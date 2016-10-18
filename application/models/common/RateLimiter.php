<?php declare(strict_types=1);
defined('BASEPATH') or exit('No direct script access allowed');

class RateLimiter extends CI_Model
{
    /**
     * based on phealNG's rate limiter
     * https://github.com/3rdpartyeve/phealng/blob/master/lib/Pheal/RateLimiter/FileLockRateLimiter.php
     */

    protected $lockFilePath;
    protected $requestsPerSecond;
    protected $maxBurst;
    protected $maxWait;

    public function __construct($requestsPerSecond = 200, $maxBurst = 300, $maxWait = 10)
    {
        $base = sys_get_temp_dir();
     
        $this->lockFilePath = join(DIRECTORY_SEPARATOR, [$base, 'crest_ratelimiter.lock']);
        $this->requestsPerSecond = $requestsPerSecond;
        $this->maxBurst = $maxBurst;
        $this->maxWait = $maxWait;
    }

    public function rateLimit()
    {
        $now = time();
        do {
            if ($this->canProceed()) {
                return true;
            }
            // Random sleep before trying again.
            usleep(mt_rand(500, 5000));

        } while (time() - $now < $this->maxWait);
    }

    protected function canProceed()
    {
        // Open file, create if does not exist
        $fp = fopen($this->lockFilePath, 'a+');
        if (!$fp) {
            throw new PhealException(
                'Cannot open rate limiter lock file ' .
                $this->lockFilePath .
                ': ' .
                error_get_last()
            );
        }
        if (flock($fp, LOCK_EX)) {
            fseek($fp, 0);
            $bucketSize = trim(fgets($fp));
            $lastRequest = trim(fgets($fp));
            $now = microtime(true);
            // Empty out slots based on time since last request
            $bucketsToFree = floor(($now - $lastRequest) * $this->requestsPerSecond);
            $bucketSize = max(0, $bucketSize - $bucketsToFree);
            if ($bucketSize < $this->maxBurst) {
                $bucketSize++;
                $lastRequest = microtime(true);
                ftruncate($fp, 0);      // truncate file
                fwrite($fp, $bucketSize . "\n");
                fputs($fp, $lastRequest . "\n");
                fflush($fp);            // flush output before releasing the lock
                flock($fp, LOCK_UN);    // release the lock
                fclose($fp);
                return true;
            } else {
                fclose($fp);
                return false;
            }
        } else {
            fclose($fp);
            return false;
        }
    }
}
