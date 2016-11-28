<?php

namespace Reliv\RcmApiLib\Middleware;

use Psr\Http\Message\ResponseInterface;
use Zend\Stratigility\Http\Request;

/**
 * Class AssetController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class AssetController
{
    /**
     *
     */
    const PARAM_FILE_PATH = 'fileName';

    /**
     * @var string
     */
    protected $path = __DIR__ . '/../../public/rcm-api-lib';

    /**
     * @var array
     */
    protected $headerMap
        = [
            '_default' => [
                'headers' => [
                    'content-type' => 'text/plain'
                ],
            ],
            'css' => [
                'headers' => [
                    'content-type' => 'text/css'
                ],
            ],
            'html' => [
                'headers' => [
                    'content-type' => 'text/html'
                ],
            ],
            'js' => [
                'headers' => [
                    'content-type' => 'application/javascript'
                ],
            ],
        ];

    /**
     * getPath
     *
     * @param $fileName
     *
     * @return null|string
     */
    protected function getPath($fileName)
    {
        $filePath = $this->path . '/' . $fileName;

        $filePath = realpath($filePath);

        // make sure file is real and is secure
        if (strpos($filePath, realpath($this->path)) === 0 && is_file($filePath)) {
            return $filePath;
        }

        return null;
    }

    /**
     * getHeaders
     *
     * @param string $fileName
     *
     * @return array|null
     */
    protected function getHeaders($fileName)
    {
        $fileExtension = $this->getFileExtension($fileName);

        if (empty($fileExtension)) {
            return $this->headerMap['_default'];
        }

        if (array_key_exists($fileExtension, $this->headerMap)) {
            return $this->headerMap[$fileExtension];
        }

        return $this->headerMap['_default'];
    }

    /**
     * getFileExtension
     *
     * @param string $fileName
     *
     * @return null
     */
    protected function getFileExtension($fileName)
    {
        $parts = pathinfo($fileName);

        if (!array_key_exists('extension', $parts)) {
            return null;
        }

        return $parts['extension'];
    }

    /**
     * getFileName
     *
     * @param Request $request
     *
     * @return string|null
     */
    protected function getFileName(Request $request)
    {
        return $request->getAttribute(
            self::PARAM_FILE_PATH
        );
    }

    /**
     * __invoke
     *
     * @param Request           $request
     * @param ResponseInterface $response
     * @param callable|null     $next
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request, ResponseInterface $response, callable $next = null)
    {
        $fileName = $this->getFileName($request);

        if (empty($fileName)) {
            return $response->withStatus(404);
        }

        $filePath = $this->getPath($fileName);

        if (empty($filePath)) {
            return $response->withStatus(404);
        }

        $headers = $this->getHeaders($fileName);

        $body = $response->getBody();

        $body->write(file_get_contents($filePath));

        foreach ($headers as $headerKey => $value) {
            $response = $response->withHeader($headerKey, $value);
        }

        return $response->withBody($body);
    }
}
