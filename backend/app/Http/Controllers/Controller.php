<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

abstract class Controller
{
    // Helper function to respond in JSON, CSV, or XML format
    protected function respond(array $data, int $status = 200, Request $request)
    {
        $acceptHeader = $request->header('Accept');

        switch ($acceptHeader) {
            case 'text/csv':
                $csvData = $this->convertToCsv($data);
                return response($csvData, $status)->header('Content-Type', 'text/csv');
            case 'application/xml':
                $xmlData = $this->arrayToXml($data);
                return response($xmlData, $status)->header('Content-Type', 'application/xml');
            default:
                return response()->json($data, $status);
        }
    }

    // Helper function to respond with error
    protected function respondWithError(String $message, int $status, Request $request)
    {
        $message = $this->handleError($status);
        return $this->respond(['message' => $message, 'error' => $message], $status, $request);
    }

    // Helper function to convert data to CSV format
    private function convertToCsv($data)
    {
        // die(json_encode($data));
        foreach($data as $key => $value)
        {
            if($key != 'message')
            {
                $fp = fopen('php://memory', 'w+');
                $header = false;

                foreach ($value as $fields) {
                    if ($fields instanceof \Illuminate\Database\Eloquent\Model || $fields instanceof \Illuminate\Support\Collection) {
                        $fields = $fields->toArray();
                    } elseif (is_object($fields)) {
                        $fields = get_object_vars($fields);
                    }
                    if(!$header) {
                        $header = true;
                        fputcsv($fp, array_keys($fields));
                    }
                    fputcsv($fp, $fields);
                }
                rewind($fp);
                $csv = stream_get_contents($fp);
                fclose($fp);
            }
        }

        return $csv;
    }

    // Helper function to convert array to XML
    private function arrayToXml(array $data, \SimpleXMLElement $xmlData = null): string
    {
        if ($xmlData === null) {
            $xmlData = new \SimpleXMLElement('<root/>');
        }

        foreach ($data as $key => $value) {
            $key = is_numeric($key) ? "item$key" : $key;

            if ($value instanceof \Illuminate\Database\Eloquent\Model || $value instanceof \Illuminate\Support\Collection) {
                $value = $value->toArray();
            } elseif (is_object($value)) {
                $value = get_object_vars($value);
            }

            if (is_array($value)) {
                $subnode = $xmlData->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xmlData->addChild($key, htmlspecialchars((string) $value));
            }
        }

        return $xmlData->asXML();
    }

    // Handle errors and generate appropriate error code
    private function handleError(int $status): string
    {
        $errorMessages = [
            // Informational responses (1xx)
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',

            // Successful responses (2xx)
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            208 => 'Already Reported',
            226 => 'IM Used',

            // Redirection messages (3xx)
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect',

            // Client error responses (4xx)
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Payload Too Large',
            414 => 'URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Range Not Satisfiable',
            417 => 'Expectation Failed',
            418 => 'I\'m a teapot',
            421 => 'Misdirected Request',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            425 => 'Too Early',
            426 => 'Upgrade Required',
            428 => 'Precondition Required',
            429 => 'Too Many Requests',
            431 => 'Request Header Fields Too Large',
            451 => 'Unavailable For Legal Reasons',

            // Server error responses (5xx)
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            508 => 'Loop Detected',
            510 => 'Not Extended',
            511 => 'Network Authentication Required',
        ];
        return $status . ": " . $errorMessages[$status] ?? 'An error occurred';
    }
}
