<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $s3client = Storage::disk('s3')->getAdapter()->getClient(); //Aws\S3\S3Client(['region' => 'us-east-1', 'version' => 'latest']);
        $contents = $s3client->listObjects([
            'Bucket'  => env('AWS_BUCKET')
        ]);
        dd($contents);
       try {
            $contents = Storage::disk('s3')->allFiles('images'); ;
            dd($contents);
            /*  = $s3client->listObjects([
                'Bucket' => env('AWS_BUCKET'),
            ]); 
            
            echo "The contents of your bucket are: \n";
            foreach ($contents['Contents'] as $content) {
                echo $content['Key'] . "\n";
            }*/
        } catch (Exception $exception) {
            echo "Failed to list objects in $bucket_name with error: " . $exception->getMessage();
            exit("Please fix error with listing objects before continuing.");
        } 
        return view('images.create');
    }

    /**
     * handle upload file
     *
     * @return \Illuminate\Http\Response
     */
    public function imageUploadPost(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $imageName = time().'.'.$request->image->extension();  
     
        $path = Storage::disk('s3')->put('images', $request->image);
        $path = Storage::disk('s3')->url($path);

        dd($path);
  
        /* Store $imageName name in DATABASE from HERE */
    
        return back()
            ->with('success','You have successfully upload image.')
            ->with('image', $path); 
    }
}
