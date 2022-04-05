<!DOCTYPE html>
<html>

<head>
    <title>laravel File Uploading with Amazon S3 - ItSolutionStuff.com.com</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">


</head>

<body>
    <div style="float: left;width: 100%;padding: 10px 5%;" class="container">

        <div class="panel panel-primary">
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

                <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <input type="file" name="file" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success">Cargar</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
        @php
        @endphp
        <div style="float: left;width: 100%;" class="row">
            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Nombre Archivo</th>
                        <th>Url Archivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contents as $object)
                    <tr>
                        <td>Tiger</td>
                        <td>prueba</td>
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
    </script>
</body>

</html>
