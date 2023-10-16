@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="pull-left">
                <h2>Create New User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}">Back</a>
            </div>
        </div>
    </div>

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {!! Form::open(array('route' => 'users.store', 'method' => 'POST')) !!}

    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <strong>Name:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Name', 'class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', null, array('placeholder' => 'Email', 'class' => 'form-control')) !!}
        </div>
    </div>

   <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <strong>Password:</strong>
            {!! Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <strong>Confirm Password:</strong>
            {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password', 'class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
            <strong>Role:</strong>
            {!! Form::select('roles[]', $roles, [], array('class' => 'form-control', 'multiple')) !!}
        </div>
    </div>


    <div class="form-group">
        <label for="status">STATUS <span style="color:red">*</span></label>
        <br>
        <select name="status" class="form-control" style="width:650px">
            <option value="">select</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    <div class="col-xs-4 col-sm-4 col-md-4 text-center mt-2">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
</div>
{!! Form::close() !!}
<p class="text-center text-primary"><small></small></p>
@endsection
