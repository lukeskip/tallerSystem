<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen - Taller</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .back {
            background-image: url('/img/back.jpg');
            background-size: cover;
            background-position: center;
        }
        .btn-brand {
            background-color: #9e915f;
            color: white;
            transition: all 0.2s ease;
        }
        .btn-brand:hover {
            background-color: #8b7f51;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 back px-4 py-8">

    <!-- Logo -->
    <div class="mb-6">
        <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 376 82" class="w-72 sm:w-80">
            <path class="fill-white" d="M32.4,23.5l-6.9,40.1h-2.5l6.9-40.1H11.4l.6-2.4h39.4l-.6,2.4h-18.4Z"/>
            <path class="fill-white" d="M73,43.5l-.8,4.9c-1.1,5.9-1.3,9.2-1.5,15.2h-2.4c0-2.2.2-4.1.3-5.9-2.7,4.1-7.3,6.2-13.4,6.2s-10.7-3.1-9.6-8.7c.9-5.3,5-8,13.7-8.9l11.1-1.1.2-1.6c1-5.3-2.7-8.5-9.4-8.5s-7.1,1-9.8,2.7l-.5-2c3.1-1.9,6.7-3,10.9-3,8.2,0,12.6,4.1,11.4,10.6ZM58.8,48.5c-7,.7-10.2,2.7-10.9,6.7-.7,4.2,2.1,6.6,8.1,6.6s12.1-3.6,13.2-10.3l.7-4-11,1.1Z"/>
            <path class="fill-white" d="M87.8,21.5l2.6-.7-5.9,36.6c-.4,2.4,1,4,3.3,4h2.4l-.6,2.3h-2.5c-3.8,0-5.8-2.5-5.2-6.1l5.8-36.1Z"/>
            <path class="fill-white" d="M105.2,21.5l2.6-.7-5.9,36.6c-.4,2.4,1,4,3.3,4h2.4l-.6,2.3h-2.5c-3.8,0-5.8-2.5-5.2-6.1l5.8-36.1Z"/>
            <path class="fill-white" d="M145.8,47.4c0,.5-.2,1-.3,1.6h-27.8c-.8,7.5,4.4,12.7,13.1,12.7s7.5-1,10.4-2.7l.5,1.9c-3.3,1.9-7.3,3-11.5,3-10.3,0-16.5-6.7-14.9-16.1,1.5-8.6,8.9-15,17.7-15s14.3,6,12.7,14.5ZM143.6,46.8c.7-6.9-3.8-11.7-10.9-11.7s-13,4.9-14.7,11.7h25.6Z"/>
            <path class="fill-white" d="M152.5,63.6l2.7-15.2c1.1-5.9,1.3-9.2,1.5-15.2h-2.4c0,3.4-.2,5.9-.5,8.6,3-5.3,8.7-9,14.7-9s2.4.2,3.5.4l-1.3,2.5c-.8-.4-1.8-.5-3-.5-7.1,0-13.6,5.7-14.9,13.1l-2.7,15.2h-2.5Z"/>
            <g>
                <path class="fill-white" d="M260.5,59.2h9.7v-31.4l-5.8,5-2.6-4.5,9.8-7.7h5.3v38.6h9.4v5.3h-25.7v-5.3Z"/>
                <path class="fill-white" d="M210,59.2h9.7v-31.4l-5.8,5-2.6-4.5,9.8-7.7h5.3v38.6h9.4v5.3h-25.7v-5.3Z"/>
                <path class="fill-white" d="M320.9,25.4c2.4,3.6,3.5,9.1,3.5,16.6s-1.3,13.3-4,17.3c-2.7,4-6.8,5.9-12.3,5.9s-9.6-1.9-12.2-5.7c-2.6-3.8-3.9-9.3-3.9-16.6,0-15.3,5.5-22.9,16.6-22.9,5.7,0,9.8,1.8,12.2,5.4ZM301.2,29.5c-1.4,2.8-2.2,7.1-2.2,13.1s.8,10.1,2.3,12.9c1.5,2.9,3.9,4.3,7.1,4.3s5.6-1.4,7-4.3c1.4-2.9,2.1-7.3,2.1-13.2s-.7-10.2-2-12.9c-1.4-2.7-3.7-4.1-7.1-4.1s-5.8,1.4-7.3,4.2Z"/>
                <path class="fill-white" d="M361.4,25.4c2.4,3.6,3.5,9.1,3.5,16.6s-1.3,13.3-4,17.3c-2.7,4-6.8,5.9-12.3,5.9s-9.6-1.9-12.2-5.7c-2.6-3.8-3.9-9.3-3.9-16.6,0-15.3,5.5-22.9,16.6-22.9,5.7,0,9.8,1.8,12.2,5.4ZM341.7,29.5c-1.4,2.8-2.2,7.1-2.2,13.1s.8,10.1,2.3,12.9c1.5,2.9,3.9,4.3,7.1,4.3s5.6-1.4,7-4.3c1.4-2.9,2.1-7.3,2.1-13.2s-.7-10.2-2-12.9c-1.4-2.7-3.7-4.1-7.1-4.1s-5.8,1.4-7.3,4.2Z"/>
                <rect class="fill-white" x="243" y="36.4" width="7.8" height="7.8"/>
            </g>
            <rect class="fill-white" x="243.9" y="54.6" width="8" height="8"/>
        </svg>
    </div>

    <!-- Login-style Card Container -->
    <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg rounded-2xl">
        
        <div id="upload-flow">
            <!-- Step 1: Select File -->
            <div id="step-select" class="text-center">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Cargar foto del concepto</h2>
                <p class="text-gray-600 text-sm mb-6">
                    Toma una foto con tu cámara o elígela de tu galería para vincularla a este item.
                </p>

                <form id="upload-form" class="space-y-4">
                    @csrf
                    <input type="file" id="file-input" name="file" accept="image/*" class="hidden">
                    
                    <button type="button" onclick="document.getElementById('file-input').click()" 
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-4 px-6 rounded-lg border border-gray-300 transition duration-200 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-camera text-lg text-gray-600"></i>
                        <span>Abrir Cámara / Galería</span>
                    </button>

                    <div id="file-preview-container" class="hidden pt-4 space-y-4">
                        <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50 p-2">
                            <img id="file-preview" src="#" alt="Preview" class="max-h-64 mx-auto rounded object-contain">
                            <button type="button" id="remove-preview" class="absolute top-4 right-4 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition shadow">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <button type="submit" 
                                class="w-full btn-brand font-bold py-3 px-6 rounded-lg shadow transition duration-200 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Subir Foto</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Step 2: Uploading -->
            <div id="step-uploading" class="hidden text-center py-8">
                <div class="relative w-16 h-16 mx-auto mb-4">
                    <div class="absolute inset-0 rounded-full border-4 border-gray-100"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-[#9e915f] border-t-transparent animate-spin"></div>
                </div>
                <h2 class="text-base font-bold text-gray-800 mb-1">Subiendo imagen...</h2>
                <p class="text-gray-500 text-xs">Por favor espera a que se complete la transferencia.</p>
            </div>

            <!-- Step 3: Success -->
            <div id="step-success" class="hidden text-center py-8">
                <div class="w-16 h-16 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-green-500/20">
                    <i class="fa-solid fa-check text-3xl text-green-600"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800 mb-1">¡Subida exitosa!</h2>
                <p class="text-gray-600 text-sm mb-6">La foto ya se sincronizó con el sistema.</p>
                <p class="text-xs text-gray-400">Ya puedes cerrar esta pestaña en tu celular.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-xs text-white/80">
        &copy; {{ date('Y') }} Taller. Todos los derechos reservados.
    </div>

    <script>
        const fileInput = document.getElementById('file-input');
        const filePreviewContainer = document.getElementById('file-preview-container');
        const filePreview = document.getElementById('file-preview');
        const removePreview = document.getElementById('remove-preview');
        const form = document.getElementById('upload-form');

        const stepSelect = document.getElementById('step-select');
        const stepUploading = document.getElementById('step-uploading');
        const stepSuccess = document.getElementById('step-success');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    filePreview.src = e.target.result;
                    filePreviewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        removePreview.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.src = '#';
            filePreviewContainer.classList.add('hidden');
        });

        function compressImage(file, maxWidth, maxHeight, quality) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = event => {
                    const img = new Image();
                    img.src = event.target.result;
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        let width = img.width;
                        let height = img.height;

                        if (width > height) {
                            if (width > maxWidth) {
                                height = Math.round((height * maxWidth) / width);
                                width = maxWidth;
                            }
                        } else {
                            if (height > maxHeight) {
                                width = Math.round((width * maxHeight) / height);
                                height = maxHeight;
                            }
                        }

                        canvas.width = width;
                        canvas.height = height;

                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob(blob => {
                            const compressedFile = new File([blob], file.name, {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });
                            resolve(compressedFile);
                        }, 'image/jpeg', quality);
                    };
                    img.onerror = error => reject(error);
                };
                reader.onerror = error => reject(error);
            });
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const file = fileInput.files[0];
            if (!file) return;

            stepSelect.classList.add('hidden');
            stepUploading.classList.remove('hidden');

            try {
                // Compressing to max 1200px and 0.7 quality (typically resulting in ~100KB-200KB)
                const compressedFile = await compressImage(file, 1200, 1200, 0.7);

                const formData = new FormData();
                formData.append('file', compressedFile);
                formData.append('_token', '{{ csrf_token() }}');

                const response = await fetch('{{ route("mobile.upload.store", ["token" => $mobileUpload->token]) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errData = await response.json().catch(() => ({}));
                    const errMsg = errData.message || errData.error || (errData.errors ? Object.values(errData.errors).flat().join(', ') : 'Error al subir la imagen');
                    throw new Error(errMsg);
                }

                stepUploading.classList.add('hidden');
                stepSuccess.classList.remove('hidden');
            } catch (error) {
                alert('Ocurrió un error: ' + error.message);
                stepUploading.classList.add('hidden');
                stepSelect.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
