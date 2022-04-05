<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <div class="container">

        <div style="float: left;width: 100%;text-align: center;margin-top: 30px;" class="panel panel-primary">
            <div class="panel-heading">
                <h2>Carga de archivos</h2>
            </div>
            <div class="panel-body">

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    <img src="{{ Session::get('image') }}">
                @endif

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> Un problema
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form style="margin: 40px 0px; " action="{{ route('file.upload') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <input type="file" name="file" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success">Cargar</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
        <div style="float: left;width: 100%;text-align: center;margin-top: 30px;" class="row">
            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Nombre Archivo</th>
                        <th>Url Archivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contents['Contents'] as $content)
                        <tr>
                            <td>{{ $content['Key'] }}</td>
                            <td>
                                <p><a onclick="getURL('{{ $content['Key'] }}');" href="javascript:">Ver enlace</a></p>
                                @if(strpos($content['Key'], 'json'))
                                <p><a onclick="getSQL('{{ $content['Key'] }}');" href="javascript:">Ver SQL</a></p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });

        function getURL(object) {
            var securitytoken = $('meta[name=csrf-token]').attr('content');
            $.ajax({
                url:  '/presigned-url',
                type: 'POST',
                data: {
                    _token: securitytoken,
                    key: object
                },
                success: function(res) {
                    console.log(res.url);
                    alert(res.url);
                },
                error: function(err) {
                    console.log(JSON.stringify(err))
                },
            });
        }

        function getSQL(object) {
            var securitytoken = $('meta[name=csrf-token]').attr('content');
            $.ajax({
                url:  '/sql-json',
                type: 'GET',
                data: {
                    _token: securitytoken,
                },
                success: function(res) {
                    let resulSQL = JSON.parse(res.select);
                    alert(resulSQL.client);
                },
                error: function(err) {
                    console.log(JSON.stringify(err))
                },
            });
        }
    </script>
</body>

</html>
