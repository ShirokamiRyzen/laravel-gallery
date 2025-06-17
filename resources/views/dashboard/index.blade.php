@extends('layouts.dash')

@section('content')
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Profile</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" id="profileForm"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Menampilkan foto profil saat ini -->
                            <div class="form-group">
                                <label for="currentAvatar"></label>
                                @php
                                    $profilePath = public_path('storage/user_profile/' . Auth::user()->profile_picture);
                                @endphp

                                <img src="{{ Auth::user()->profile_picture && file_exists($profilePath)
        ? asset('storage/user_profile/' . Auth::user()->profile_picture)
        : asset('assets/default-avatar.jpg') }}" alt="Current Avatar" class="img-fluid mb-2" style="max-width: 200px;">
                            </div>
                            <div class="form-group">
                                <label for="avatar"></label>
                                <input type="file" class="form-control-file" id="avatar" name="Avatar">
                            </div>
                            <div class="form-group">
                                <label for="name">Full Name:</label>
                                <input type="text" class="form-control" id="name" name="NamaLengkap"
                                    value="{{ Auth::user()->NamaLengkap }}">
                            </div>
                            <div class="form-group">
                                <label for="name">Username:</label>
                                <input type="text" class="form-control" id="name" name="Username"
                                    value="{{ Auth::user()->Username }}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="Email"
                                    value="{{ Auth::user()->Email }}">
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <textarea class="form-control" id="address" rows="3"
                                    name="Alamat">{{ Auth::user()->Alamat }}</textarea>
                            </div>

                            <br>
                            <button type="submit" class="btn btn-primary" id="submitButton">Save Changes</button>
                        </form>
                        @if(Auth::user()->profile_picture)
                            <form action="{{ route('profile.deletePicture') }}" method="POST" id="deletePictureForm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mt-3">Delete Picture</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('profileForm').addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to update your profile!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
    <script>
        document.getElementById('deletePictureForm').addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete your profile picture!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
            });
        @endif
    </script>
@endsection