@extends('layouts.dash')
@section('title', 'Album')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Photos in Album "{{ $album->NamaAlbum }}"
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse($fotos as $foto)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img src="{{ Storage::url($foto->LokasiFile) }}" class="card-img-top" alt="{{ $foto->JudulFoto }}" loading="lazy">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ Str::limit($foto->JudulFoto, 40) }}</h5>
                                            <p class="card-text">{{ Str::limit($foto->DeskripsiFoto, 40) }}</p>
                                        </div>
                                        <div class="card-footer d-flex align-items-center">
                                            <a href="{{ route('home.show', $foto->id) }}" class="btn btn-primary me-2"><i class="fa-solid fa-eye"></i></a>
                                            <a href="{{ route('foto.edit', $foto->id) }}" class="btn btn-primary me-2"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form id="delete-form-{{ $foto->id }}"
                                                  action="{{ route('foto.destroy', $foto->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger delete-btn me-2"
                                                        data-foto-id="{{ $foto->id }}"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12">
                                    <p class="text-center">No photos in this album.</p>
                                    <p class="text-center">¯\_(ツ)_/¯</p>
                                </div>
                            @endforelse
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
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const fotoId = this.getAttribute('data-foto-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You cant revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + fotoId).submit();
                        }
                    });
                });
            });
        });
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
