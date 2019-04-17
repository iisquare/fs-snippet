<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
error_reporting(E_ERROR);
ini_set('display_errors', 'Off');
// API From: https://developers.theta360.com/en/docs/v2.1/api_reference/getting_started.html
$cmd = isset($_GET['cmd']) ? $_GET['cmd'] : '';
$input = file_get_contents('php://input');
$name = 'R0010038.JPG';
$filename = 'images/'.$name;
$fileUri = 'widget/theta360/'.$filename;
switch ($cmd) {
    case 'status':
        $output = [
            'name' => 'camera.takePicture',
            'state' => 'done',
            'results' => [
                'fileUri' => $fileUri
            ]
        ];
        break;
    default:
        $json = json_decode($input, true);
        switch ($json['name']) {
            case 'camera._listAll':
                $output = [
                    'entries' => [],
                    'totalEntries' => 4,
                    'continuationToken' => strval(rand(101, 200))
                ];
                for ($i = 0; $i < $json['parameters']['entryCount']; $i++) {
                    $output['entries'][] = [
                        'name' => $name,
                        'uri' => $fileUri,
                        'size' => 1461998,
                        'dateTime' => date('Y-m-d H:i:s'),
                        'dateTimeZone' => date('Y-m-d H:i:s+08:00'),
                        'width' => 5376,
                        'height' => 2688
                    ];
                }
                break;
            case 'camera._getLivePreview':
                header('Content-Type: image/jpeg');
                $output = file_get_contents($filename);
                break;
            case 'camera.startSession':
                $output = [
                    'name' => 'camera.startSession',
                    'state' => 'done',
                    'results' => [
                        'sessionId' => 'SID_0001',
                        'timeout' => 180
                    ]
                ];
                break;
            case 'camera.updateSession':
                $output = [
                    'name' => 'camera.updateSession',
                    'state' => 'done',
                    'results' => [
                        'sessionId' => 'SID_0001',
                        'timeout' => 180
                    ]
                ];
                break;
            case 'camera.setOptions':
                $output = '';
                break;
            case 'camera.takePicture':
                $output = [
                    'name' => 'camera.takePicture',
                    'state' => 'inProgress',
                    'id' => strval(rand(1, 100)),
                    'progress' => [
                        'completion' => 0.00
                    ]
                ];
                break;
            case 'camera.closeSession':
                $output = '';
                break;
            default:
                if (rand(0, 1)) {
                    $output = [
                        'name' => 'camera.takePicture',
                        'state' => 'inProgress',
                        'id' => strval(rand(1, 100)),
                        'progress' => [
                            'completion' => 0.00
                        ]
                    ];
                } else {
                    $output = [
                        'name' => 'camera.startSession',
                        'state' => 'done',
                        'results' => [
                            'sessionId' => 'SID_0001',
                            'timeout' => 180
                        ]
                    ];
                }
        }
}
file_put_contents('logs/simulator.log', var_export([
    'server' => $_SERVER,
    'cmd' => $cmd,
    'input' => $input,
    'output' => $output
], true), FILE_APPEND);
echo is_array($output) ? json_encode($output) : $output;
