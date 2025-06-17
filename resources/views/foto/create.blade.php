@extends('layouts.dash')
@section('title', 'Add Photo')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">Add Photo</div>
                    <div class="card-body">
                        <form action="{{ route('foto.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="judul_foto">Title</label>
                                <input type="text" class="form-control" id="judul_foto" name="judul_foto" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="deskripsi_foto">Photo Description</label>
                                <textarea class="form-control" id="deskripsi_foto" name="deskripsi_foto" rows="3"
                                    required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="album_id">Select Album</label>
                                <select class="form-select" id="album_id" name="album_id" required>
                                    @foreach($albums as $album)
                                        <option value="{{ $album->id }}">{{ $album->NamaAlbum }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="foto">Upload Photo</label>
                                <input type="file" id="foto" name="foto" accept="image/*" style="display: none;">
                                <div id="drop-area" class="border py-3 px-4 text-center">
                                    <p id="drop-text" class="mb-0">Drag and drop your files here</p>
                                    <p class="mb-0">or</p>
                                    <label for="foto" class="btn btn-primary">Browse Files</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
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

        // drag over
        dropArea.addEventListener('dragover', e => {
            e.preventDefault();
            dropArea.classList.add('border-primary');
        });
        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('border-primary');
        });

        // drop handler
        dropArea.addEventListener('drop', e => {
            e.preventDefault();
            dropArea.classList.remove('border-primary');

            let files = e.dataTransfer.files;
            // pakai DataTransfer untuk reliably set fileInput.files
            let dt = new DataTransfer();
            Array.from(files).forEach(f => dt.items.add(f));

            fileInput.files = dt.files;
            showFileNames(dt.files);

            // trigger change kalau ada listener lain
            fileInput.dispatchEvent(new Event('change'));
        });

        // manual browse
        fileInput.addEventListener('change', () => {
            showFileNames(fileInput.files);
        });

        function showFileNames(files) {
            if (files.length) {
                dropText.innerText = 'Files Added: ' +
                    Array.from(files).map(f => f.name).join(', ');
            } else {
                dropText.innerText = 'No files selected';
            }
        }
    </script>

    <script>
        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Success', text: "{{ session('success') }}" });
        @elseif (session('error'))
            Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error') }}" });
        @endif
    </script>
@endsection