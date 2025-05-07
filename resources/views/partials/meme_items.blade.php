@if ($memes->count() === 0)
    <p class="text-align-center">Pas de mèmes crées pour le moment.</p>
@else
    @foreach ($memes as $meme)
        <div class="col-md-3 mb-4">
            <img src="{{ asset('storage/memes/' . $meme->filename) }}" class="img-fluid rounded shadow meme-thumb"
                alt="Mème" data-toggle="modal" data-target="#previewModal"
                data-image="{{ asset('storage/memes/' . $meme->filename) }}" title="Cliquez pour visualiser"
                style="cursor: pointer">
        </div>
    @endforeach
@endif
