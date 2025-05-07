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
                                    Vous pouvez déplacer les textes ajouté partout sur l'image
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
        const previews = document.getElementById("previews");
        const addTextFieldBtn = document.getElementById("addTextField");
        const textFieldsContainer = document.getElementById("textFieldsContainer");
        const downloadBtn = document.getElementById("downloadMeme");
        const shareBtn = document.getElementById("shareMeme");
        const memeForm = document.getElementById("memeForm");
        const submitBtn = document.querySelector('#memeForm button[type="submit"]');

        let uploadedImage = null;
        let textFields = [];
        let draggableTexts = [];
        let selectedText = null;
        let offsetX, offsetY;

        // Upload image
        imageUploader.addEventListener("click", () => {
            const input = document.createElement("input");
            input.type = "file";
            input.accept = "image/*";
            input.addEventListener("change", (event) => {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = new Image();
                        img.onload = function() {
                            canvas.width = 500;
                            canvas.height = (img.height / img.width) * 500;
                            uploadedImage = img;
                            drawMeme();
                            updateButtonsState();
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
            input.click();
        });

        // Ajouter un champ texte
        addTextFieldBtn.addEventListener("click", () => {
            const textField = document.createElement("input");
            textField.type = "text";
            textField.placeholder = "Entrez votre texte";
            textField.className = "form-control mt-2";
            textFieldsContainer.appendChild(textField);
            textFields.push(textField);

            // Ajout du texte déplaçable
            const y = 50 + draggableTexts.length * 40;
            draggableTexts.push({
                x: canvas.width / 2,
                y: y,
                content: "",
                field: textField
            });

            textField.addEventListener("input", () => {
                const obj = draggableTexts.find(t => t.field === textField);
                if (obj) obj.content = textField.value.toUpperCase();
                drawMeme();
            });
        });

        // Dessiner
        function drawMeme() {
            if (!uploadedImage) return;

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(uploadedImage, 0, 0, canvas.width, canvas.height);

            ctx.font = "24px Arial";
            ctx.fillStyle = "white";
            ctx.strokeStyle = "black";
            ctx.lineWidth = 2;
            ctx.textAlign = "center";

            draggableTexts.forEach(obj => {
                if (obj.content.trim()) {
                    ctx.fillText(obj.content, obj.x, obj.y);
                    ctx.strokeText(obj.content, obj.x, obj.y);
                }
            });

            // Signature watermark
            ctx.font = "16px Arial";
            ctx.fillStyle = "rgba(255,255,255,0.7)";
            ctx.textAlign = "right";
            ctx.fillText("memeflow.app", canvas.width - 10, canvas.height - 10);
        }

        // Drag & Drop sur canvas
        canvas.addEventListener("mousedown", (e) => {
            const rect = canvas.getBoundingClientRect();
            const mouseX = e.clientX - rect.left;
            const mouseY = e.clientY - rect.top;

            selectedText = draggableTexts.find(obj => {
                const textWidth = ctx.measureText(obj.content).width;
                return mouseX >= obj.x - textWidth / 2 &&
                    mouseX <= obj.x + textWidth / 2 &&
                    mouseY >= obj.y - 20 && mouseY <= obj.y + 10;
            });

            if (selectedText) {
                offsetX = mouseX - selectedText.x;
                offsetY = mouseY - selectedText.y;
            }
        });

        canvas.addEventListener("mousemove", (e) => {
            if (!selectedText) return;
            const rect = canvas.getBoundingClientRect();
            selectedText.x = e.clientX - rect.left - offsetX;
            selectedText.y = e.clientY - rect.top - offsetY;
            drawMeme();
        });

        canvas.addEventListener("mouseup", () => {
            selectedText = null;
        });

        // Télécharger le mème
        downloadBtn.addEventListener("click", () => {
            const link = document.createElement("a");
            link.download = `meme_${Date.now()}.png`;
            link.href = canvas.toDataURL("image/png");
            link.click();
        });

        // Partage
        shareBtn.addEventListener("click", async () => {
            if (navigator.share) {
                canvas.toBlob(async (blob) => {
                    const file = new File([blob], "meme.png", {
                        type: "image/png"
                    });
                    try {
                        await navigator.share({
                            title: "Mon mème",
                            files: [file]
                        });
                    } catch (err) {
                        alert("Le partage a échoué.");
                        console.error(err);
                    }
                });
            } else {
                alert("Le partage n’est pas supporté sur cet appareil.");
            }
        });

        // Soumission AJAX
        memeForm.addEventListener("submit", function(e) {
            e.preventDefault();
            drawMeme(); // Redessine proprement

            const imageData = canvas.toDataURL("image/png");

            const formData = new FormData();
            formData.append("image", imageData);

            fetch(this.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "/";
                    } else {
                        alert("Erreur : " + (data.error || "Échec de l'enregistrement."));
                    }
                })
                .catch(error => {
                    alert("Erreur lors de l'envoi du mème.");
                    console.error(error);
                });
        });

        // État des boutons
        function updateButtonsState() {
            const hasImage = !!uploadedImage;
            downloadBtn.disabled = !hasImage;
            shareBtn.disabled = !hasImage;
            submitBtn.disabled = !hasImage;
        }

        updateButtonsState();
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
