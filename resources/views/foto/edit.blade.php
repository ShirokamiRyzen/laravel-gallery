@extends('layouts.dash')
@section('title', 'Edit Photo')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Edit Photo
                    </div>
                    <div class="card-body">
                        <form id="editForm" action="{{ route('foto.update', $foto->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="judul_foto" class="form-label">Title</label>
                                <input type="text" class="form-control" id="judul_foto" name="judul_foto"
                                    value="{{ $foto->JudulFoto }}">
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi_foto" class="form-label">Photo Description</label>
                                <textarea class="form-control" id="deskripsi_foto" name="deskripsi_foto">{{ $foto->DeskripsiFoto }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="album_id" class="form-label">Album</label>
                                <select class="form-select" id="album_id" name="album_id">
                                    @foreach ($albums as $album)
                                        <option value="{{ $album->id }}"
                                            {{ $foto->id_album == $album->id ? 'selected' : '' }}>{{ $album->NamaAlbum }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="foto" name="foto"
                                    style="display: none;">
                                <div id="drop-area" class="border py-3 px-4 text-center">
                                    <p id="drop-text" class="mb-0">Drag and drop your files here</p>
                                    <p class="mb-0">or</p>
                                    <label for="foto" class="btn btn-primary">Browse Files</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Fungsi drag and drop
        const dropArea = document.getElementById('drop-area');
    
        dropArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropArea.classList.add('border-primary');
        });
    
        dropArea.addEventListener('dragleave', function() {
            dropArea.classList.remove('border-primary');
        });
    
        dropArea.addEventListener('drop', function(e) {
            e.preventDefault();
            dropArea.classList.remove('border-primary');
            const fileList = e.dataTransfer.files;
            document.getElementById('foto').files = fileList;
    
            // Menampilkan nama file yang diunggah
            showFileNames(fileList);
        });
    
        // Manual file selection functionality
        const fileInput = document.getElementById('foto');
        fileInput.addEventListener('change', function() {
            const fileList = fileInput.files;
    
            // Menampilkan nama file yang diunggah
            showFileNames(fileList);
        });
    
        function showFileNames(files) {
            const dropText = document.getElementById('drop-text');
            if (files.length > 0) {
                let fileNames = 'Files Added: ';
                for (let i = 0; i < files.length; i++) {
                    fileNames += files[i].name;
                    if (i !== files.length - 1) {
                        fileNames += ', ';
                    }
                }
                dropText.innerText = fileNames;
            } else {
                dropText.innerText = 'No files selected';
            }
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
