<?php

namespace Reliv\RcmApiLib\Model;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ExceptionApiMessage extends ApiMessage
{
    /**
     * @var string type for fields
     */
    protected $typeName = 'exception';

    /**
     * @param \Throwable $exception
     * @param array      $params
     * @param bool|true  $primary
     */
    public function __construct(
        \Throwable $exception,
        $params = [],
        $primary = true
    ) {
        $this->build($exception, $params);
        $this->setPrimary($primary);
    }

    /**
     * @param \Throwable $exception
     * @param array      $params
     *
     * @return void
     */
    public function build(\Throwable $exception, $params = [])
    {
        $exceptionParams = [];

        if (method_exists($exception, 'getParms')) {
            // @todo this should be in its own class
            $exceptionParams = $exception->getParms();
        }

        if (is_array($exceptionParams)) {
            $params = array_merge($exceptionParams, $params);
        }

        $code = $exception->getCode();

        if (empty($code)) {
            $code = null;
        }

        $this->setType($this->typeName);
        $this->setValue($exception->getMessage());
        $this->setSource($this->getSourceString($exception));
        $this->setCode($code);
        $this->setParams($params);
    }

    /**
     * getSourceString
     *
     * @param \Throwable $exception
     *
     * @return string
     */
    public function getSourceString(\Throwable $exception)
    {
        $className = get_class($exception);
        if ($pos = strrpos($className, '\\')) {
            $className = lcfirst(substr($className, $pos + 1));
        }

        return $className;
    }
}
