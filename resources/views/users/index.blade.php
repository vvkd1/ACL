@extends('layouts.app')

@section('content')

    <!-- Include necessary styles and scripts -->
<div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
     <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</div>
<div class="container">

    <div class="row">
        <div class="col-lg-12">
            <h2 class="pull-left">Users Management</h2>
            <div class="pull-right" style="margin-bottom: 15px; float:right;">
             <a class="btn btn-outline-danger" href="/generatetcpdf">cell Pdf <i class="fas fa-file-pdf"></i></a>
            <a class="btn btn-outline-danger" href="/pdf">Pdf <i class="fas fa-file-pdf"></i></a>
                <a class="btn btn-outline-success" href="/export">Export <i class="fas fa-file-export"></i></a>
                <a class="btn btn-success" href="{{ route('users.create') }}"><i class="fa-sharp fa-solid fa-plus" style="color: #fafafa; font-size:25px"></i></a>
                
            </div>
        </div>
    </div>
    

    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif

    <div>
        <table class="table table-bordered" id="myTable">
            <thead>
                <tr class='text-center'>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $user)
                <tr>
                    <td class='text-center'>{{$loop->iteration}}</td>
                    <td class='text-center'>{{ $user->name }}</td>
                    <td class='text-center'>{{ $user->email }}</td>
                    <td class='text-center'>
                        @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $v)
                        <span class="badge rounded-pill bg-success">{{ $v }}</span>
                        @endforeach
                        @endif
                    </td>
                    <td class='text-center'>
                        <a class="btn btn-outline-warning btn-sm" href="{{ route('users.show',$user->id) }}">Show</a>
                        <a class="btn btn-outline-success btn-sm" href="{{ route('users.edit',$user->id) }}">Edit</a>
                        {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-outline-danger btn-sm ']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>




<!-- Initialize DataTables within a document.ready block -->
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
<script>
$(document).ready(function() {
    
    $(".alert-success").fadeOut(2000);
});
</script>

@endsection
