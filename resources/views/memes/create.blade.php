<style>
    /* Cacher les icônes d'action (check et croix) de Dropzone */
    .dz-success-mark,
    .dz-error-mark,
    .dz-remove {
        display: none !important;
    }

    .shadow__btn {
        padding: 10px 20px;
        border: none;
        font-size: 13px;
        color: #fff;
        border-radius: 7px;
        letter-spacing: 4px;
        font-weight: 700;
        text-transform: uppercase;
        transition: 0.5s;
        transition-property: box-shadow;
    }

    .shadow__btn {
        background: rgb(0, 140, 255);
        box-shadow: 0 0 25px rgb(0, 140, 255);
    }

    .shadow__btn:hover {
        box-shadow: 0 0 5px rgb(0, 140, 255),
            0 0 25px rgb(0, 140, 255),
            0 0 50px rgb(0, 140, 255),
            0 0 100px rgb(0, 140, 255);
    }

    /* Espace entre les blocs de texte */
    #textFieldsContainer>div {
        margin-bottom: 1rem;
    }

    /* Alignement du color picker + bouton */
    .color-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .form-control-color {
        width: 50px !important;
        height: 38px !important;
        padding: 2px;
    }

    /* Bouton X */
    .btn-danger {
        font-weight: bold;
        padding: 0 10px;
        margin-left: 10px;
        font-size: 24px;
        line-height: 1.5;
    }

    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }
</style>

<!-- Meme Modal -->
<div class="modal fade" id="memeModal" tabindex="-1" role="dialog" aria-labelledby="memeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form id="memeForm" method="POST" enctype="multipart/form-data" action="{{ route('memes.store') }}">
            @csrf
            <input type="hidden" name="meme_image" id="meme_image">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer votre mème</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Formulaires -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Téléversez votre image</label><br>

                                <!-- Bouton custom qui déclenche l'input caché -->
                                <div class="btn fileinput-button" id="imageUploader">
                                    <ion-icon name="camera-outline"></ion-icon>
                                </div>
                                <!-- Input caché réel -->
                                <input type="file" id="imageInput" accept="image/*" style="display: none;" />
                                <div id="previews" class="mt-3"></div>
                            </div>

                            <div class="form-group">
                                <label>Textes à ajouter</label><br>
                                <span><ion-icon name="information-circle-outline"
                                        style="vertical-align: middle; display; inline; color: blue; font-size: 20px;"></ion-icon>
                                    Vous pouvez déplacer les textes ajoutés partout sur l'image
                                </span><br>
                                <span><ion-icon name="information-circle-outline"
                                        style="vertical-align: middle; display; inline; color: blue; font-size: 20px;"></ion-icon>
                                    Choisir la couleur qui vous conviendra le mieux
                                </span>
                                <div id="textFieldsContainer"></div>
                                <button type="button" class="btn btn-sm mt-2" id="addTextField">Ajouter un champ
                                    texte</button>
                            </div>
                        </div>

                        <!-- Aperçu -->
                        <div class="col-md-6 text-center">
                            <canvas id="memeCanvas" width="500" height="450"
                                style="border:1px solid #ccc;"></canvas>
                            <div class="mt-3 mr-4 d-flex justify-content-end" style="gap: 1rem;">
                                <button type="button" class="shadow__btn" id="downloadMeme" title="Télécharger">
                                    <ion-icon name="download-outline"></ion-icon> Télécharger
                                </button>
                                <button type="button" class="shadow__btn" id="shareMeme" title="Partager">
                                    <ion-icon name="share-social-outline"></ion-icon> Partager
                                </button>
                                <!-- ajoute id="generateMeme" et retire type="submit" -->
                                <button type="submit" class="shadow__btn" title="Générer">
                                    <ion-icon name="color-wand"></ion-icon> Générer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById("memeCanvas");
        const ctx = canvas.getContext("2d");
        const imageUploader = document.getElementById("imageUploader");
        const addTextFieldBtn = document.getElementById("addTextField");
        const textFieldsContainer = document.getElementById("textFieldsContainer");
        const downloadBtn = document.getElementById("downloadMeme");
        const shareBtn = document.getElementById("shareMeme");
        const memeForm = document.getElementById("memeForm");
        const submitBtn = document.querySelector('#memeForm button[type="submit"]');

        let uploadedImage = null;
        let draggableTexts = [];
        let selectedText = null;
        let offsetX, offsetY;

        imageUploader.addEventListener("click", () => {
            const input = document.createElement("input");
            input.type = "file";
            input.accept = "image/*";
            input.onchange = e => {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = () => {
                    const img = new Image();
                    img.onload = () => {
                        canvas.width = 500;
                        canvas.height = (img.height / img.width) * 500;
                        uploadedImage = img;
                        drawMeme();
                        updateButtonsState();
                    };
                    img.src = reader.result;
                };
                reader.readAsDataURL(file);
            };
            input.click();
        });

        addTextFieldBtn.addEventListener("click", () => {
            const id = Date.now();

            const container = document.createElement("div");
            container.className = "mb-3";

            const textInput = document.createElement("input");
            textInput.type = "text";
            textInput.placeholder = "Entrez votre texte";
            textInput.className = "form-control";
            textInput.dataset.id = id;

            const colorLabel = document.createElement("label");
            colorLabel.innerText = "Choisir la couleur :";
            colorLabel.className = "form-label mt-2";

            const controlRow = document.createElement("div");
            controlRow.className = "d-flex align-items-center gap-2";

            const colorPicker = document.createElement("input");
            colorPicker.type = "color";
            colorPicker.value = "#FFFFFF";
            colorPicker.className = "form-control form-control-color";
            colorPicker.style.width = "60px";
            colorPicker.dataset.id = id;

            const removeBtn = document.createElement("button");
            removeBtn.type = "button";
            removeBtn.className = "btn btn-sm btn-danger";
            removeBtn.innerText = "×";
            removeBtn.title = "Supprimer";

            removeBtn.addEventListener("click", () => {
                textFieldsContainer.removeChild(container);
                draggableTexts = draggableTexts.filter(t => t.id !== id);
                drawMeme();
                updateButtonsState();
            });

            controlRow.appendChild(colorPicker);
            controlRow.appendChild(removeBtn);

            container.appendChild(textInput);
            container.appendChild(colorLabel);
            container.appendChild(controlRow);

            textFieldsContainer.appendChild(container);

            draggableTexts.push({
                id,
                content: "",
                x: canvas.width / 2,
                y: 50 + draggableTexts.length * 40,
                color: "#FFFFFF"
            });

            textInput.addEventListener("input", () => {
                const obj = draggableTexts.find(t => t.id === id);
                obj.content = textInput.value.toUpperCase();
                drawMeme();
                updateButtonsState();
            });

            colorPicker.addEventListener("input", () => {
                const obj = draggableTexts.find(t => t.id === id);
                obj.color = colorPicker.value;
                drawMeme();
            });

            updateButtonsState();
        });

        function drawMeme() {
            if (!uploadedImage) return;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(uploadedImage, 0, 0, canvas.width, canvas.height);

            draggableTexts.forEach(obj => {
                if (!obj.content) return;
                ctx.font = "24px Impact";
                ctx.fillStyle = obj.color;
                ctx.strokeStyle = "black";
                ctx.lineWidth = 4;
                ctx.textAlign = "center";
                ctx.textBaseline = "top";
                ctx.strokeText(obj.content, obj.x, obj.y);
                ctx.fillText(obj.content, obj.x, obj.y);
            });

            ctx.font = "16px Arial";
            ctx.fillStyle = "rgba(255,255,255,0.7)";
            ctx.textAlign = "right";
            ctx.fillText("memeflow.app", canvas.width - 10, canvas.height - 20);
        }

        canvas.addEventListener("mousedown", e => {
            const rect = canvas.getBoundingClientRect();
            const mx = e.clientX - rect.left;
            const my = e.clientY - rect.top;
            selectedText = draggableTexts.find(obj => {
                const w = ctx.measureText(obj.content).width;
                return mx >= obj.x - w / 2 && mx <= obj.x + w / 2 && my >= obj.y && my <= obj
                    .y + 24;
            });
            if (selectedText) {
                offsetX = mx - selectedText.x;
                offsetY = my - selectedText.y;
            }
        });

        canvas.addEventListener("mousemove", e => {
            if (!selectedText) return;
            const rect = canvas.getBoundingClientRect();
            selectedText.x = e.clientX - rect.left - offsetX;
            selectedText.y = e.clientY - rect.top - offsetY;
            drawMeme();
        });

        canvas.addEventListener("mouseup", () => selectedText = null);
        canvas.addEventListener("mouseleave", () => selectedText = null);

        downloadBtn.addEventListener("click", () => {
            const link = document.createElement("a");
            link.download = `meme_${Date.now()}.png`;
            link.href = canvas.toDataURL("image/png");
            link.click();
        });

        shareBtn.addEventListener("click", () => {
            if (!navigator.share) return alert("Partage non supporté");
            canvas.toBlob(blob => {
                const file = new File([blob], "meme.png", {
                    type: "image/png"
                });
                navigator.share({
                    files: [file],
                    title: "Mon mème"
                }).catch(console.error);
            });
        });

        memeForm.addEventListener("submit", function(e) {
            e.preventDefault();
            drawMeme();
            const imageData = canvas.toDataURL("image/png");
            const formData = new FormData();
            formData.append("image", imageData);

            fetch(this.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) window.location.href = "/";
                    else alert(data.error || "Erreur.");
                })
                .catch(err => {
                    console.error(err);
                    alert("Erreur lors de l'envoi du mème.");
                });
        });

        function updateButtonsState() {
            const hasImage = !!uploadedImage;
            const hasText = draggableTexts.some(t => t.content && t.content.trim().length > 0);
            const enabled = hasImage && hasText;

            downloadBtn.disabled = !enabled;
            shareBtn.disabled = !enabled;
            submitBtn.disabled = !enabled;
        }

        updateButtonsState(); // Initialisation au chargement
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Modal de prévisualisation -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title">Prévisualisation du mème</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="previewImage" src="" class="img-fluid mb-3" alt="Mème en grand">
                <div class="d-flex justify-content-center gap-3">
                    <a id="downloadButton" class="btn mr-2" download>
                        <i class="fas fa-download"></i> Télécharger
                    </a>
                    <button id="shareButton" class="btn">
                        <i class="fas fa-share-alt"></i> Partager
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const previewModal = document.getElementById('previewModal');
    const previewImage = document.getElementById('previewImage');
    const downloadButton = document.getElementById('downloadButton');
    const shareButton = document.getElementById('shareButton');

    // Lorsqu'on clique sur une image
    document.querySelectorAll('.meme-thumb').forEach(img => {
        img.addEventListener('click', () => {
            const imageUrl = img.getAttribute('data-image');
            previewImage.src = imageUrl;
            downloadButton.href = imageUrl;
        });
    });

    // Partage avec Web Share API
    shareButton.addEventListener('click', async () => {
        const imageUrl = previewImage.src;
        try {
            const response = await fetch(imageUrl);
            const blob = await response.blob();
            const file = new File([blob], "memeflow.png", {
                type: blob.type
            });

            if (navigator.canShare && navigator.canShare({
                    files: [file]
                })) {
                await navigator.share({
                    files: [file],
                    title: 'Mème à partager',
                    text: 'Regarde ce mème !'
                });
            } else {
                alert("Le partage automatique n'est pas pris en charge sur ce navigateur.");
            }
        } catch (error) {
            console.error("Erreur de partage :", error);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Récupère les éléments
        const topText = document.getElementById('top_text');
        const bottomText = document.getElementById('bottom_text');
        const downloadBtn = document.getElementById('downloadMeme');
        const shareBtn = document.getElementById('shareMeme');
        const submitBtn = document.querySelector('#memeForm button[type="submit"]');

        // Fonction qui gère l'état disabled/enabled
        function updateButtonsState() {
            // On active si au moins un champ non vide
            const hasText = topText.value.trim().length > 0 || bottomText.value.trim().length > 0;
            downloadBtn.disabled = !hasText;
            shareBtn.disabled = !hasText;
            submitBtn.disabled = !hasText;
        }

        // Au chargement de la page, on désactive tout
        updateButtonsState();

        // Sur chaque changement de texte, on met à jour l'état
        [topText, bottomText].forEach(input => {
            input.addEventListener('input', updateButtonsState);
        });
    });
</script>
