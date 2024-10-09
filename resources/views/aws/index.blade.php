<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tải lên tệp lên S3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</head>
</head>

<body>
    <div class="container p-4">
        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <p style="color: red;">{{ $errors->first() }}</p>
        @endif

        <form action="{{ route('aws.upload') }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf
            <div class="col-auto">
                <input class="form-control" id="formFileLg" type="file" id="file" name="file" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Upload</button>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">File</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $file)
                <tr>
                    <th scope="row">1</th>
                    <td>
                        <a href="{{ 'https://nta-dev-s3-1.s3.ap-southeast-1.amazonaws.com/'.$file['Key'] }}">
                            <img src="{{ 'https://nta-dev-s3-1.s3.ap-southeast-1.amazonaws.com/'.$file['Key'] }}" title="{{ $file['Owner']['DisplayName'] }}" alt="{{ $file['Owner']['DisplayName'] }}" width="100px" height="100px" />
                        </a>
                    </td>
                    <td>
                        <form action="{{ route('aws.delete') }}" method="post" style="display:inline;">
                            @csrf
                            <input type="hidden" name="file_key" value="{{ $file['Key'] }}">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tệp này?');">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
