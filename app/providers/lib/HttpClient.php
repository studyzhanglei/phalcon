<?php
namespace  App\Providers\Lib;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class HttpClient
{
    private $di;

    public function __construct(\Phalcon\Di\FactoryDefault $di)
    {
        $this->di   = $di;
    }

    public function __call($name, $arguments)
    {
        $name   = strtoupper($name);
        if (!in_array($name, ['POST', 'GET', 'PUT'])) {
            return false;
        }

        $requestData    = [$arguments[0]];
        array_push($requestData, $name);

        unset($arguments[0]); //unset url

        $requestData    = array_merge($requestData, $arguments);
        return $this->request(...$requestData);
    }

    public function request($url, $method = 'GET', $data = [], $options = [], $jsonDecode = true)
    {
        $request  = new \Phalcon\Http\Request();
        $default  = [
            'jsonDecode' => true,
            'base_uri'   => '',
            'timeout'    => 5,
            'headers'    => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'X-Request-Id' => $request->getServer('X-Request-Id'),
            ],
        ];
        $options = array_merge($default, $options);
        $client  = new Client($options);

        try {
            if ('application/json' == $options['headers']['Content-Type']) {
                $requestOptions = ['json' => $data];
            } else {
                $requestOptions = ['form_params' => $data];
            }

            if ('GET' === strtoupper($method) ) {
                $response = $client->request($method, $url, [
                    'json' => [],
                    'query' => $data,
                ]);

            } else {
                $response = $client->request($method, $url, $requestOptions);
            }

            if (200 != $response->getStatusCode()) {
                return false;
            }

            $response = $response->getBody()->getContents();

            $this->di->get('log')->info(
                sprintf('请求url：%s 请求参数：%s 响应结果：%s', $url, json_encode($requestOptions), json_encode($response))
            );

            if ($jsonDecode) {
                return json_decode($response, true);
            }

            return $response;
        } catch (BadResponseException $e) {
            $this->di->get('log')->error(
                sprintf('请求url：%s 请求参数：%s 错误信息：%s', $url, json_encode($requestOptions), json_encode($e->getTrace()))
            );
            return false;
        } catch (\Exception $e) {
            $this->di->get('log')->error(
                sprintf('请求url：%s 请求参数：%s 错误信息：%s', $url, json_encode($requestOptions), json_encode($e->getTrace()))
            );
            return false;
        }
    }
}