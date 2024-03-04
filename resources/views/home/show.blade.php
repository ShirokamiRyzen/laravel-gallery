@extends('layouts.app')
@section('title', 'Detail Foto')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <img src="{{ Storage::url($foto->LokasiFile) }}" class="img-fluid" alt="{{ $foto->JudulFoto }}"
                            loading="lazy">
                        <h2 class="mt-4">{{ $foto->JudulFoto }}</h2>
                        <p class="mb-4">{{ $foto->DeskripsiFoto }}</p>
                        <p>Uploaded by: {{ $foto->user->Username }}</p>
                        <div class="row">
                            <div class="col">
                                <a href="{{ Storage::url($foto->LokasiFile) }}" class="btn btn-primary" download><i
                                        class="fa-solid fa-download"></i> Download</a>
                            </div>
                            <div class="col text-end">
                                @guest
                                    {{--<button class="btn btn-primary" disabled><i class="fa-regular fa-heart"></i></button>--}}
                                    <span class="text-muted"></span>
                                @else
                                    @if ($foto->isLikedByUser(auth()->id()))
                                        <form action="{{ route('foto.unlike', $foto->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger like-btn" data-foto-id="{{ $foto->id }}"><i
                                                    class="fa-solid fa-heart"></i></button>
                                        </form>
                                    @else
                                        <form action="{{ route('foto.like', $foto->id) }}" method="post">
                                            @csrf
                                            <button class="btn btn-primary like-btn" data-foto-id="{{ $foto->id }}"><i
                                                    class="fa-regular fa-heart"></i></button>
                                        </form>
                                    @endif
                                @endguest
                                <p id="total-likes"><i class="fa-solid fa-heart"></i> {{ $foto->total_likes }}&nbsp;</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comment Section -->
        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <h3>Comment</h3>
                <!-- List Komentar -->
                <div id="comment-list">
                    @if ($foto->komentar && count($foto->komentar) > 0)
                        <ul class="list-unstyled">
                            @foreach ($foto->komentar as $index => $komentar)
                                <div class="comment-container" style="{{ $index >= 10 ? 'display: none;' : '' }}">
                                    @if ($komentar->user)
                                        <div class="d-flex align-items-center">
                                            @if ($komentar->user->profile_picture)
                                                <img src="{{ asset('storage/user_profile/' . $komentar->user->profile_picture) }}"
                                                    alt="Profile Picture" class="rounded-circle me-2" style="width: 40px;">
                                            @else
                                                <img src="{{ asset('assets/default-avatar.jpg') }}" alt="Profile Picture"
                                                    class="rounded-circle me-2" style="width: 40px;">
                                            @endif
                                            <div>
                                                <strong>{{ $komentar->user->Username }}</strong>
                                                <p>{{ $komentar->IsiKomentar }}</p>
                                            </div>
                                        </div>
                                        @auth
                                            @if ($komentar->id_user == auth()->id())
                                                <div>
                                                    <form action="{{ route('foto.deleteComment', $komentar->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                                class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    @else
                                        <p>{{ $komentar->IsiKomentar }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </ul>
                        @if (count($foto->komentar) > 10)
                            <div class="text-center mt-3">
                                <button class="btn btn-primary load-more-btn mb-3">Load More Comments</button>
                            </div>
                        @endif
                    @else
                        <p>Empty comment</p>
                    @endif
                </div>

                <!-- Form Komentar -->
                @auth
                    <form id="comment-form" action="{{ route('foto.komentar', $foto->id) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <textarea id="comment-text" name="IsiKomentar" class="form-control" rows="3" placeholder="Add a comment..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Send</button>
                    </form>
                @else
                    <p><a href="/login">Login to comment</a></p>
                @endauth
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Menggunakan event delegasi untuk memastikan bahwa tombol "Load More Comments" juga berfungsi untuk elemen baru yang ditambahkan dinamis
            $(document).on('click', '.load-more-btn', function() {
                $('.comment-container:hidden').slice(0, 10).show();
                if ($('.comment-container:hidden').length == 0) {
                    $('.load-more-btn').hide();
                }
            });

            // Hanya jalankan jika pengguna telah login
            @auth
            $('.like-btn').click(function(e) {
                e.preventDefault();
                var fotoId = $(this).data('foto-id');
                var likeBtn = $(this);
                var isUnlike = likeBtn.hasClass('btn-danger'); // Check if it's an unlike action

                $.ajax({
                    type: isUnlike ? 'DELETE' :
                    'POST', // Change request type based on unlike action
                    url: isUnlike ? "/public/photo/" + fotoId + "/unlike" : "/public/photo/" +
                        fotoId + "/like",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        var totalLikes = response.total_likes;
                        $('#total-likes').html('<i class="fa-solid fa-heart"></i> ' +
                            totalLikes + '&nbsp;');

                        // Toggle button text and class for unlike action
                        if (isUnlike) {
                            likeBtn.removeClass('btn-danger').addClass('btn-primary').html(
                                '<i class="fa-regular fa-heart"></i>');
                        } else {
                            likeBtn.removeClass('btn-primary').addClass('btn-danger').html(
                                '<i class="fa-solid fa-heart"></i>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $('#comment-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    success: function(response) {
                        $('#comment-text').val('');
                        var comment = `
                            <div class="comment-container">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/user_profile/' . auth()->user()->profile_picture) }}" alt="Profile Picture" class="rounded-circle me-2" style="width: 40px;">
                                    <div>
                                        <strong>{{ auth()->user()->Username }}</strong>
                                        <p>${response.comment.IsiKomentar}</p>
                                    </div>
                                </div>
                                <div>
                                    <form action="{{ route('foto.deleteComment', '') }}/${response.comment.id}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                            `;
                        $('#comment-list').append(comment);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        @endauth
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
