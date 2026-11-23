<?php

namespace App\Helper;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class ApiHelper
{
    public static function sendRequest($url, $method, $data = [], $authToken = '', $header = [])
    {
        $method = strtolower($method);

        $defaultHeaders = [
            'Accept' => 'application/json',
        ];

        if (! empty($authToken)) {
            $defaultHeaders['Authorization'] = 'Bearer '.$authToken;
        }

        $mergedHeaders = array_merge($defaultHeaders, $header);

        try {
            $response = Http::asJson()
                ->timeout(300)
                ->connectTimeout(30)
                ->withHeaders($mergedHeaders)
                ->$method($url, $data);

            return $response->object();
        } catch (\Throwable $e) {
            return (object) [
                'status' => 'error',
                'message' => 'Gagal menghubungi server: '.$e->getMessage(),
            ];
        }
    }

    // Fungsi khusus untuk mengirimkan file (upload)
    public static function sendRequestWithFile($url, $method, $data = [], $authToken = '', $fileKey = 'file', $file = null)
    {
        $method = strtolower($method);

        try {
            $request = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$authToken,
            ]);

            if ($file) {
                $response = $request->attach(
                    $fileKey,
                    fopen($file->getRealPath(), 'r'),
                    $file->getClientOriginalName()
                )->$method($url, $data);
            } else {
                $response = $request->$method($url, $data);
            }

            return $response->object();
        } catch (\Throwable $e) {
            return (object) [
                'status' => 'error',
                'message' => 'Gagal menghubungi server: '.$e->getMessage(),
            ];
        }
    }

    // Fungsi khusus untuk mengirimkan file yang sudah dalam format base64
    public static function sendRequestWithFileBase64($url, $method, $data = [], $authToken = '', $fileKey = 'file', $base64File = null, $fileName = null)
    {
        try {
            $method = strtolower($method);

            $request = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$authToken,
            ]);

            // Jika ada file base64, tambahkan ke data
            if ($base64File) {
                $response = $request->attach(
                    $fileKey,
                    base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64File)),
                    $fileName ?? 'photo.png'
                )->$method($url, $data);
            } else {
                $response = $request->$method($url, $data);
            }

            return $response->object();
        } catch (\Throwable $e) {
            return (object) [
                'status' => 'error',
                'message' => 'Gagal menghubungi server: '.$e->getMessage(),
            ];
        }
    }

    // Fungsi untuk HTTP streaming
    public static function sendStreamingRequest($url, $method, $data = [], $authToken = '', ?callable $callback = null)
    {
        $method = strtolower($method);

        try {
            $client = new Client([
                'timeout' => 600,
                'connect_timeout' => 30,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$authToken,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $options = [];

            if ($method === 'post' || $method === 'put' || $method === 'patch') {
                $options['json'] = $data;
            }

            // Gunakan streaming response
            $response = $client->request($method, $url, $options + [
                'stream' => true,  // Aktifkan streaming
            ]);

            $body = $response->getBody();

            // Baca stream per chunk
            $content = '';
            while (! $body->eof()) {
                $chunk = $body->read(1024);  // Baca 1KB per chunk

                // Jika ada callback, proses chunk
                if ($callback && is_callable($callback)) {
                    $callback($chunk);
                }

                $content .= $chunk;

                // Flush output jika di web
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }

            return json_decode($content) ?: (object) ['status' => 'success'];
        } catch (\Throwable $e) {
            return (object) [
                'status' => 'error',
                'message' => 'Gagal menghubungi server: '.$e->getMessage(),
            ];
        }
    }
}



