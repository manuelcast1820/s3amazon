<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $s3client = Storage::disk('s3')->getAdapter()->getClient();
        $contents = $s3client->listObjects([
            'Bucket'  => env('AWS_BUCKET')
        ]);
        foreach ($contents['Contents'] as $content) {
            $extension = explode(".", $content['Key']);
            if( $extension[1] == "json"){
                $result = $s3client->selectObjectContent([
                    'Bucket' => env('AWS_BUCKET'), 
                    'Key' => $content['Key'], 
                    'ExpressionType' => 'SQL', 
                    'Expression' => 'SELECT s.client FROM S3Object s',
                    'InputSerialization' => [
                       // 'CompressionType' => 'GZIP',
                        'JSON' => [
                            'Type' => 'LINES'
                           /*  'FileHeaderInfo' => 'USE', 
                            'RecordDelimiter' => "\n", 
                            'FieldDelimiter' => '.', */
                        ],
                    ], 
                    'OutputSerialization' => [
                        'JSON' => [],
                    ],
                ]);
                foreach ($result['Payload'] as $event) {
                    if (isset($event['Records'])) {
                        echo (string) $event['Records']['Payload'] . PHP_EOL;
                    } 
                }
            }
        }
        return view('files.index');
    }

    public function fileUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|max:10240',
        ]);
    
        $path = Storage::disk('s3')->put('files', $request->file);
        $path = Storage::disk('s3')->url($path);

    
        return back()
            ->with('success','Se agrego el archivo.')
            ->with('file', $path); 
    }
}
