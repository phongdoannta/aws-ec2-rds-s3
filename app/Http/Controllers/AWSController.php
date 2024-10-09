<?php

namespace App\Http\Controllers;

use App\Models\AWS;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Illuminate\Http\Request;

class AWSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $s3 = new S3Client([
            'region'  => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $bucket = env('AWS_BUCKET');

        try {
            $result = $s3->listObjects([
                'Bucket' => $bucket,
            ]);

            return view('aws.index', [
                'files' => $result['Contents'] ?? []
            ]);
        } catch (AwsException $e) {
            return back()->withErrors(['list_error' => 'Lỗi khi lấy danh sách tệp: ' . $e->getMessage()]);
        }
        // return view('aws.index');
    }

    /**
     * Upload file S3
     */
    public function uploadFileS3(Request $request)
    {
        // $request->validate([
        //     'file' => 'required|file|max:2048', // Giới hạn kích thước tệp (2MB)
        // ]);

        $s3 = new S3Client([
            'region'  => env('AWS_DEFAULT_REGION'), // Lấy từ .env
            'version' => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'), // Lấy từ .env
                'secret' => env('AWS_SECRET_ACCESS_KEY'), // Lấy từ .env
            ],
        ]);

        $bucket = env('AWS_BUCKET'); // Lấy từ .env
        $file = $request->file('file');
        $key = basename($file->getClientOriginalName());
        // $key = basename($file['name']);

        try {
            // Tải lên tệp
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key'    => $key,
                'SourceFile' => $file->getRealPath(),
                'ACL'    => 'public-read', // Cài đặt quyền truy cập
            ]);
            return redirect()->route('aws.list')->with('success', "Tệp đã được tải lên thành công: " . $result['ObjectURL']);
        } catch (AwsException $e) {
            return redirect()->route('aws.list')->with('error', "Lỗi khi tải lên: " . $e->getMessage());
        }
    }

    /**
     * Delete file S3
     */
    public function deleteFileS3(Request $request)
    {
        // $request->validate([
        //     'file_key' => 'required|string',
        // ]);

        $s3 = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $bucket = env('AWS_BUCKET');
        $fileKey = $request->input('file_key');

        try {
            // Xóa tệp
            $s3->deleteObject([
                'Bucket' => $bucket,
                'Key' => $fileKey,
            ]);
            return back()->with('success', 'Tệp đã được xóa thành công.');
        } catch (AwsException $e) {
            return back()->withErrors(['error' => 'Lỗi khi xóa tệp: ' . $e->getMessage()]);
        }
    }
}
