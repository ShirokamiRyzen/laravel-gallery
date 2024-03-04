@extends('layouts.dash')
@section('title', 'Make Album')

@section('content')
<br>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Add Album</div>
                <div class="card-body">
                    <form action="{{ route('album.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="NamaAlbum" class="form-label">Album Name</label>
                            <input type="text" class="form-control" id="NamaAlbum" name="NamaAlbum">
                        </div>
                        <div class="mb-3">
                            <label for="Deskripsi" class="form-label">Description</label>
                            <textarea class="form-control" id="Deskripsi" name="Deskripsi"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
