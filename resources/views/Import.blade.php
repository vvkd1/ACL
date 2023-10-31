@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Import Excel Data</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('import.excel') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="excel-file">Choose Excel File</label>
                            <input type="file" name="excel_file" id="excel-file" accept=".xls, .xlsx" class="custom-file-input form-control-sm">
                        </div>
                        <button type="submit" class="btn btn-custom btn-sm">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles for the file input */
    .custom-file-input {
        border: 1px solid #ccc;
        padding: 5px;
        border-radius: 5px;
        font-size: 14px;
    }

    /* Custom styles for the button */
    .btn-custom {
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
        font-size: 14px;
    }
</style>

<script>
    $(document).ready(function() {
        $(".alert-success").fadeOut(2000);
    });
</script>
@endsection
