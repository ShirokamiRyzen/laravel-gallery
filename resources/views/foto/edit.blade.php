@extends('layouts.dash')
@section('title', 'Edit Photo')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Photo</div>
                    <div class="card-body">
                        <form id="editForm" action="{{ route('foto.update', $foto->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label" for="judul_foto">Title</label>
                                <input type="text" class="form-control" id="judul_foto" name="judul_foto"
                                    value="{{ $foto->JudulFoto }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="deskripsi_foto">Photo Description</label>
                                <textarea class="form-control" id="deskripsi_foto" name="deskripsi_foto" rows="3"
                                    required>{{ $foto->DeskripsiFoto }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="album_id">Album</label>
                                <select class="form-select" id="album_id" name="album_id" required>
                                    @foreach($albums as $a)
                                        <option value="{{ $a->id }}" {{ $foto->id_album == $a->id ? 'selected' : '' }}>
                                            {{ $a->NamaAlbum }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="foto">Change Photo</label>
                                <input type="file" id="foto" name="foto" accept="image/*" style="display:none">
                                <div id="drop-area" class="border py-3 px-4 text-center">
                                    <p id="drop-text" class="mb-0">Drag & drop here</p>
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
        const fileInput = document.getElementById('foto');
        const dropArea = document.getElementById('drop-area');
        const dropText = document.getElementById('drop-text');

        dropArea.addEventListener('dragover', e => {
            e.preventDefault();
            dropArea.classList.add('border-primary');
        });
        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('border-primary');
        });

        dropArea.addEventListener('drop', e => {
            e.preventDefault();
            dropArea.classList.remove('border-primary');

            const files = e.dataTransfer.files;
            const dt = new DataTransfer();
            Array.from(files).forEach(f => dt.items.add(f));
            fileInput.files = dt.files;

            dropText.innerText =
                'Files: ' + Array.from(dt.files).map(x => x.name).join(', ');
        });

        fileInput.addEventListener('change', () => {
            dropText.innerText =
                'Files: ' + Array.from(fileInput.files).map(x => x.name).join(', ');
        });
    </script>

    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Success', text: "{{ session('success') }}" })
        @elseif(session('error'))
            Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error') }}" })
        @endif
    </script>
@endsection