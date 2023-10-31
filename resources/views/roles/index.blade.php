@extends('layouts.app')

@section('content')
<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Role Management</h2>
            </div>
            <div class="pull-right" style="float:right; margin-bottom:15px;">
                @can('role-create')
                <a class="btn btn-success" href="{{ route('roles.create') }}"> <i class="fa-sharp fa-solid fa-plus" style="color: #fafafa; font-size:25px"></i></a>
                @endcan
            </div>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<table class="table table-bordered" id="myTable">
    <thead>
        <tr class='text-center'>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
       @foreach ($roles as $key => $role)
    <tr class='text-center'>
        <td>{{ ++$i }}</td>
        <td>{{ $role->name }}</td>
        <td>
            <a class="btn btn-outline-warning btn-sm" href="{{ route('roles.show', $role->id) }}">Show</a>
            @can('role-edit')
                <a class="btn btn-outline-success btn-sm" href="{{ route('roles.edit', $role->id) }}">Edit</a>
            @endcan
            @can('role-delete')
                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
            @endcan
        </td>
    </tr>
@endforeach

    </tbody>
</table>
{!! $roles->render() !!}
<p class="text-center text-primary"><small></small></p>

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
