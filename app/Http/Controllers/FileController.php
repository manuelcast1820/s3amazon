<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {

        $s3client = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);

        $contents = $s3client->listObjects([
            'Bucket'  => env('AWS_BUCKET')
        ]);
        return view('files.index', compact('contents'));
    }

    public function fileUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|max:10240',
        ]);

        $path = Storage::disk('s3')->put('files', $request->file);
        $path = Storage::disk('s3')->url($path);



        return back()
            ->with('success', 'Se agrego el archivo.')
            ->with('file', $path);
    }

    public function listFiles()
    {

        $s3client = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);

        //$s3client = Storage::disk('s3')->getAdapter()->getClient();
        $contents = $s3client->listObjects([
            'Bucket'  => env('AWS_BUCKET')
        ]);

        $expiry = "+10 minutes";

        /* 

        $request = $s3client->createPresignedRequest($cmd, $expiry); */

        //$presignedUrl = (string)$request->getUri();

        foreach ($contents['Contents'] as $content) {
            $extension = //explode(".", $content['Key']);
                $path = 'Luzca 22x65.png'; //$content['Key'];

            $cmd = $s3client->getCommand('GetObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $path,
                // 'ACL' => 'public-read',
            ]);
            $request = $s3client->createPresignedRequest($cmd, $expiry);

            $presignedUrl = (string)$request->getUri();
            dd($presignedUrl);
        }

        return view('files.list', compact('contents'));
    }

    public function sqlJson(Request $request): JsonResponse
    {
        $response = [];
        $s3client = Storage::disk('s3')->getAdapter()->getClient();
        $contents = $s3client->listObjects([
            'Bucket'  => env('AWS_BUCKET')
        ]);
        foreach ($contents['Contents'] as $content) {
            $extension = explode(".", $content['Key']);
            $path = $content['Key'];
            /* $image = "https://analysis-qaroni.s3.amazonaws.com/$path";
            echo '<img src="' . $image . '" width="100" />'; */
            /*  dd($content); */
            if ($extension[1] == "json") {
                $result = $s3client->selectObjectContent([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => $content['Key'],
                    'ExpressionType' => 'SQL',
                    'Expression' => 'SELECT s.client FROM S3Object s',
                    'InputSerialization' => [
                        // 'CompressionType' => 'GZIP',
                        'JSON' => [
                            'Type' => 'LINES'
                            //  'FileHeaderInfo' => 'USE', 
                            //    'RecordDelimiter' => "\n", 
                            //    'FieldDelimiter' => '.', 
                        ],
                    ],
                    'OutputSerialization' => [
                        'JSON' => [],
                    ],
                ]);
                foreach ($result['Payload'] as $event) {
                    if (isset($event['Records'])) {
                        $response[] = (string) $event['Records']['Payload']; //. PHP_EOL;
                    }
                }
            }
        }
        return response()->json([
            'select' => $response
        ]);
    }

    public function presignedUrl(Request $request): JsonResponse
    {
        $response = [];
        $s3client = Storage::disk('s3')->getAdapter()->getClient();
        $contents = $s3client->listObjects([
            'Bucket'  => env('AWS_BUCKET')
        ]);
        $contents = $s3client->listObjects([
            'Bucket'  => env('AWS_BUCKET')
        ]);

        $expiry = "+10 minutes";

        /* 

        $request = $s3client->createPresignedRequest($cmd, $expiry); */

        //$presignedUrl = (string)$request->getUri();

        //foreach ($contents['Contents'] as $content) {
            $path = $request->key; //$content['Key'];

            $cmd = $s3client->getCommand('GetObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $path,
            ]);
            $request = $s3client->createPresignedRequest($cmd, $expiry);
            $presignedUrl = (string)$request->getUri();
        //}
        return response()->json([
            'url' => $presignedUrl
        ]);
    }
}
