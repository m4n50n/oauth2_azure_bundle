<?php

declare(strict_types=1);

/**
 * This file is part of the OAuth2AzureBundle package.
 *
 * (c) Jose Clemente García Rodríguez aka m4n50n <josegarciarodriguez89@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @author Jose Clemente García Rodríguez <josegarciarodriguez89@hotmail.com>
 * 
 * @link https://github.com/m4n50n/oauth2_azure_bundle
 */

namespace M4n50n\OAuth2AzureBundle\Trait;

/**
 * Class StandardResponseTrait
 *
 * @method int    getCode()     Status code of the request
 * @method bool   isError()     If the request returned an error
 * @method int    getMessage()  Message returned from the request
 * @method mixed  getData()     Data returned from the request
 * @method mixed  getObject()   Additional object returned from the request
 */
trait StandardResponseTrait
{
    /**
     * @var int The HTTP status code of the response.
     */
    protected $code = 200;

    /**
     * @var bool Indicates if an error occurred in the response.
     */
    protected $error = false;

    /**
     * @var string The message associated with the response.
     */
    protected $message = "";

    /**
     * @var mixed The data payload of the response.
     */
    protected $data;

    /**
     * @var mixed An additional object associated with the response.
     */
    protected $object;

    /**
     * Sets the HTTP status code of the response.
     *
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
    }

    /**
     * Gets the HTTP status code of the response.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Sets whether an error occurred in the response.
     *
     * @param bool $error
     */
    public function setError(bool $error)
    {
        $this->error = $error;
    }

    /**
     * Checks if an error occurred in the response.
     *
     * @return bool
     */
    public function isError(): bool
    {
        return $this->error;
    }

    /**
     * Sets the message associated with the response.
     *
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * Gets the message associated with the response.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Sets the data payload of the response.
     *
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Gets the data payload of the response.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets an additional object associated with the response.
     *
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * Gets the additional object associated with the response.
     *
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Initializes the response with optional values.
     *
     * @param int|null    $code
     * @param bool|null   $error
     * @param string|null $message
     * @param mixed       $data
     * @param mixed|null  $object
     */
    public function initResponse(?int $code = null, ?bool $error = null, ?string $message = null, $data = null, $object = null)
    {
        $this->code = $code ?? $this->code;
        $this->error = $error ?? $this->error;
        $this->message = $message ?? $this->message;
        $this->data = $data ?? $this->data;
        $this->object = $object ?? $this->object;
    }

    /**
     * Sets the response values based on an associative array.
     *
     * @param array $response
     */
    public function setResponse(array $response)
    {
        $this->code = $response['code'] ?? $this->code;
        $this->error = $response['error'] ?? $this->error;
        $this->message = $response['message'] ?? $this->message;
        $this->data = $response['data'] ?? $this->data;
        $this->object = $response['object'] ?? $this->object;

        $this->initResponse($this->code, $this->error, $this->message, $this->data, $this->object);
    }

    /**
     * Gets the response in the specified format.
     *
     * @param string|null $format
     *
     * @return mixed
     */
    public function getResponse(?string $format = 'array')
    {
        switch ($format) {
            case 'json':
                return json_encode([
                    'code' => $this->code,
                    'error' => $this->error,
                    'message' => $this->message,
                    'data' => $this->data,
                    'object' => $this->object,
                ]);
            case 'array':
                return [
                    'code' => $this->code,
                    'error' => $this->error,
                    'message' => $this->message,
                    'data' => $this->data,
                    'object' => $this->object,
                ];
            case 'values':
                return [
                    $this->code,
                    $this->error,
                    $this->message,
                    $this->data,
                    $this->object,
                ];
            default:
                return null;
        }
    }
}
