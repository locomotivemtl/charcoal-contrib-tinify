<?php

namespace Charcoal\Tinify\Action;

use Charcoal\App\Action\AbstractAction;
use Charcoal\Tinify\Helper\CallbackStream;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

/**
 * Optimize Action
 */
class CompressEventAction extends AbstractAction
{
    /**
     * Gets a psr7 request and response and returns a response.
     *
     * Called from `__invoke()` as the first thing.
     *
     * @param RequestInterface  $request  A PSR-7 compatible Request instance.
     * @param ResponseInterface $response A PSR-7 compatible Response instance.
     * @return ResponseInterface
     */
    public function run(RequestInterface $request, ResponseInterface $response)
    {
        $this->setMode(self::MODE_EVENT_STREAM);

        return $response;
    }

    /**
     * @return \Generator
     */
    private function optimizeFiles()
    {
        for ($i = 1; $i <= 10; $i++) {
            yield [
                'id'       => 'test_'.$i,
                'text'     => sprintf('%s / %s', $i, 10),
                'progress' => floor(100 / 10 * $i)
            ];
            sleep(3);
        }
    }

    /**
     * Returns an associative array of results (set after being  invoked / run).
     *
     * The raw array of results will be called from `__invoke()`.
     *
     * @return array|mixed
     */
    public function results()
    {
        foreach ($this->optimizeFiles() as $file) {
            echo 'data: '.json_encode($file).PHP_EOL.PHP_EOL;

            ob_flush();
            flush();
        }

        return [];
    }
}
