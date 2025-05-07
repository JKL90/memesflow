@extends('layouts.app')

@section('title', 'MemesFlow')
@section('page-title', 'Memes gallerie')
@section('content')

    <style>
        .btn {
            background-color: #00BFA6;
            padding: 14px 40px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            border-radius: 10px;
            border: 2px dashed #00BFA6;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
            transition: .4s;
        }

        .btn span:last-child {
            display: none;
        }

        .btn:hover {
            transition: .4s;
            border: 2px dashed #00BFA6;
            background-color: #fff;
            color: #00BFA6;
        }

        .btn:active {
            background-color: #87dbd0;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-secondary"><ion-icon name="flower-outline" style="vertical-align: middle; display; inline; color: blue;"></ion-icon> 
                        Gallerie
                    </h1>
                </div>
                <div class="col-12">
                    <div class="card card-default">
                        <div class="card-header d-flex w-100 justify-content-end align-items-center">
                            <button type="button" class="btn" data-toggle="modal" data-target="#memeModal">
                                Créer votre mème
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="meme-container" class="row">
                                @include('partials.meme_items')
                            </div>

                            <div class="text-center mt-4">
                                @if ($memes->hasMorePages())
                                    <button id="load-more" class="btn">Charger plus</button>
                                @endif
                            </div>
                            {{-- <div class="row">
                                @foreach ($memes as $meme)
                                    <div class="col-md-3 mb-4">
                                        <img src="{{ asset('storage/memes/' . $meme->filename) }}"
                                            class="img-fluid rounded shadow meme-thumb" alt="Mème" data-toggle="modal"
                                            data-target="#previewModal"
                                            data-image="{{ asset('storage/memes/' . $meme->filename) }}" class="meme-image"
                                            title="Cliquez pour visualiser" style="cursor: pointer">
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                {{ $memes->links() }}
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('memes.create')

    <script>
        let page = 2;

        document.getElementById('load-more')?.addEventListener('click', function() {
            fetch(`?page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(data => {
                    const container = document.getElementById('meme-container');
                    container.insertAdjacentHTML('beforeend', data);
                    page++;

                    // Vérifie si c'est la dernière page
                    if (!data.trim()) {
                        document.getElementById('load-more').style.display = 'none';
                    }
                });
        });
    </script>
@endsection
