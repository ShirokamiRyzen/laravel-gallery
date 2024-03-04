@extends('layouts.dash')
@section('title', 'Photo Menu')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                Photos Data
                            </div>
                            <div class="col text-end">
                                <a href="{{ route('foto.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Photo</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <label for="album_id">Filter:</label>
                        <select name="album_id" id="album_id" onchange="applyFilter()">
                            <option value="">All Albums</option>
                            @foreach($albums as $album)
                                <option value="{{ $album->id }}" {{ Request::get('album_id') == $album->id ? 'selected' : '' }}>{{ $album->NamaAlbum }}</option>
                            @endforeach
                        </select>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Album</th>
                                    <th>Publisher</th>
                                    <th>Create At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fotos as $foto)
                                    <tr>
                                        <td>{{ ($fotos->currentPage() - 1) * $fotos->perPage() + $loop->iteration }}</td>
                                        <td><img src="{{ Storage::url($foto->LokasiFile) }}" alt="Foto" style="max-width: 100px; max-height: 100px;" loading="lazy"></td>
                                        <td>{{ Str::limit($foto->JudulFoto, 40) }}</td>
                                        <td>{{ Str::limit($foto->DeskripsiFoto, 40) }}</td>
                                        <td>{{ $foto->album->NamaAlbum }}</td>
                                        <td>{{ $foto->user->NamaLengkap }}</td>
                                        <td>{{ $foto->created_at }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('foto.edit', $foto->id) }}" class="btn btn-primary me-2"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <form id="delete-foto-{{ $foto->id }}" action="{{ route('foto.destroy', $foto->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="deleteFoto({{ $foto->id }})" class="btn btn-danger me-2"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                @if ($fotos->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $fotos->appends(request()->input())->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                                    </li>
                                @endif
                            
                                @for ($i = 1; $i <= $fotos->lastPage(); $i++)
                                    <li class="page-item {{ $fotos->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $fotos->appends(request()->input())->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                            
                                @if ($fotos->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $fotos->appends(request()->input())->nextPageUrl() }}" aria-label="Next">&raquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">&raquo;</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <script>
        function deleteFoto(id) {
            Swal.fire({
                title: 'Confirm',
                text: "Are you sure you want to delete this photo?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form untuk menghapuskan data
                    document.getElementById('delete-foto-' + id).submit();
                }
            });
        }

        function applyFilter() {
            var albumId = document.getElementById('album_id').value;
            window.location.href = "{{ route('foto.filterByAlbum') }}" + "?album_id=" + albumId;
        }
    </script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
            });
        @elseif (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
        @endif
    </script>
@endsection
