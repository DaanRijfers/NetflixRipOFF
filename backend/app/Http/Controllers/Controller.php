<?php

namespace App\Http\Controllers;

abstract class Controller
{
    // Helper function to respond in JSON, CSV, or XML format
    private function respond(Request $request, array $data, int $status = 200)
    {
        $acceptHeader = $request->header('Accept');

        switch ($acceptHeader) {
            case 'text/csv':
                $csvData = $this->convertToCsv($data);
                return response($csvData, $status)->header('Content-Type', 'text/csv');
            case 'text/xml':
                $xmlData = $this->arrayToXml($data);
                return response($xmlData, $status)->header('Content-Type', 'text/xml');
            default:
                return response()->json($data, $status);
        }
    }

    // Helper function to respond with error
    private function respondWithError(Request $request, int $status)
    {
        $message = $this->handleError($status);
        return $this->respond($request, ['error' => $message], $status);
    }

    // Helper function to convert data to CSV format
    private function convertToCsv($data)
    {
        $csv = '';
        $header = false;

        foreach ($data as $row) {
            if (!$header) {
                // Add the header row
                $csv .= implode(',', array_keys((array)$row)) . "\n";
                $header = true;
            }
            // Add the data rows
            $csv .= implode(',', array_values((array)$row)) . "\n";
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
            if (is_array($value)) {
                $subnode = $xmlData->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xmlData->addChild($key, htmlspecialchars("$value"));
            }
        }

        return $xmlData->asXML();
    }

    // Handle errors and generate appropriate error code
    private function handleError(int $status): string
    {
        $errorMessages = $this->getHttpErrorMessages();
        return $errorMessages[$status] ?? 'An error occurred';
    }

    // Get all HTTP error messages
    private function getHttpErrorMessages(): array
    {
        return [
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
            418 => 'I’m a teapot',
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
    }
}
