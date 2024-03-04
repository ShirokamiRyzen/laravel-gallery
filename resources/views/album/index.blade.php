@extends('layouts.dash')
@section('title', 'Album Menu')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                Albums Data
                            </div>
                            <div class="col text-end">
                                <a href="{{ route('album.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add Album</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Album Name</th>
                                    <th>Description</th>
                                    <th>Publisher</th>
                                    <th>Create At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($albums as $album)
                                    <tr>
                                        <td>{{ ($albums->currentPage() - 1) * $albums->perPage() + $loop->iteration }}</td>
                                        <td>{{ Str::limit($album->NamaAlbum, 40) }}</td>
                                        <td>{{ Str::limit($album->Deskripsi, 40) }}</td>
                                        <td>{{ $album->user->NamaLengkap }}</td>
                                        <td>{{ $album->created_at }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('album.show', $album->id) }}"
                                                    class="btn btn-success me-2"><i class="fa-solid fa-eye"></i></a>
                                                <a href="{{ route('album.edit', $album->id) }}"
                                                    class="btn btn-primary me-2"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <form id="delete-album-{{ $album->id }}"
                                                    action="{{ route('album.destroy', $album->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="deleteAlbum({{ $album->id }})"
                                                        class="btn btn-danger me-2"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                @if ($albums->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $albums->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                                    </li>
                                @endif
                                
                                @for ($i = 1; $i <= $albums->lastPage(); $i++)
                                    <li class="page-item {{ $i === $albums->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $albums->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                @if ($albums->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $albums->nextPageUrl() }}" aria-label="Next">&raquo;</a>
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
        <br>
    </div>
    <script>
        function deleteAlbum(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Album will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-album-' + id).submit();
                }
            });
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
