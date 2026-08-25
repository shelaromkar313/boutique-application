<script>
function virtualTryOn() {
    return {
        isOpen: false,
        activeTab: 'models',
        productId: '',
        productName: '',
        productPrice: 0,
        garmentImage: '',
        category: 'upper_body',
        userImage: null,
        resultImage: null,
        isLoading: false,
        tryOnError: null,
        retryable: false,
        retryCountdown: 0,
        sliderPos: 50,
        cameraStream: null,
        loadingTip: 'Initializing CatVTON (ICLR 2025) diffusion model...',
        
        demoModels: [
            {
                id: 'model-1',
                name: 'Aanya (Standard)',
                height: "5'6\"",
                size: 'S / M',
                image: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=700&q=80'
            },
            {
                id: 'model-2',
                name: 'Rhea (Petite)',
                height: "5'3\"",
                size: 'XS / S',
                image: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=700&q=80'
            },
            {
                id: 'model-3',
                name: 'Priya (Curvy)',
                height: "5'7\"",
                size: 'L / XL',
                image: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=700&q=80'
            },
            {
                id: 'model-4',
                name: 'Mira (Tall)',
                height: "5'9\"",
                size: 'M / L',
                image: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=700&q=80'
            }
        ],
        selectedModel: null,

        openModal(detail) {
            if (!detail) return;
            this.productId = detail.id || '';
            this.productName = detail.name || 'Designer Outfit';
            this.productPrice = detail.price || 0;
            this.garmentImage = detail.image || '';
            this.resultImage = null;
            this.tryOnError = null;
            this.retryable = false;
            this.sliderPos = 50;
            this.isOpen = true;

            // Auto-detect cloth category
            if (detail.category) {
                const catLower = String(detail.category).toLowerCase();
                if (catLower.includes('dress') || catLower.includes('gown') || catLower.includes('lehenga') || catLower.includes('saree') || catLower.includes('overall')) {
                    this.category = 'dresses';
                } else if (catLower.includes('bottom') || catLower.includes('pant') || catLower.includes('skirt') || catLower.includes('lower')) {
                    this.category = 'lower_body';
                } else {
                    this.category = 'upper_body';
                }
            } else {
                this.category = 'upper_body';
            }

            // Default to first demo model if no image selected
            if (!this.userImage && this.demoModels.length > 0) {
                this.selectDemoModel(this.demoModels[0]);
            }
        },

        closeModal() {
            this.stopCamera();
            this.isOpen = false;
        },

        selectDemoModel(model) {
            this.selectedModel = model;
            this.resultImage = null;
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = () => {
                const canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth || 700;
                canvas.height = img.naturalHeight || 900;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                try {
                    this.userImage = canvas.toDataURL('image/jpeg', 0.92);
                } catch (e) {
                    this.userImage = model.image;
                }
            };
            img.onerror = () => {
                this.userImage = model.image;
            };
            this.userImage = model.image;
            img.src = model.image;
        },

        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.userImage = e.target.result;
                    this.resultImage = null;
                    this.selectedModel = null;
                };
                reader.readAsDataURL(file);
            }
        },

        async startCamera() {
            try {
                this.cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                if (this.$refs.videoElement) {
                    this.$refs.videoElement.srcObject = this.cameraStream;
                }
            } catch (err) {
                console.warn('Camera access denied or unavailable:', err);
                alert('Could not access camera. Please upload a photo instead.');
                this.activeTab = 'upload';
            }
        },

        stopCamera() {
            if (this.cameraStream) {
                this.cameraStream.getTracks().forEach(track => track.stop());
                this.cameraStream = null;
            }
        },

        capturePhoto() {
            const video = this.$refs.videoElement;
            if (!video) return;
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            this.userImage = canvas.toDataURL('image/jpeg');
            this.resultImage = null;
            this.stopCamera();
            this.activeTab = 'upload';
        },

        async generateTryOn() {
            if (!this.userImage || !this.garmentImage) return;

            this.isLoading = true;
            this.resultImage = null;
            this.tryOnError = null;
            this.retryCountdown = 0;

            const tips = [
                '🐈 Initializing CatVTON (ICLR 2025) diffusion engine...',
                '🧠 Computing DensePose & SCHP body human parsing masks...',
                '👗 Concatenating garment latents with UNet spatial attention...',
                '✨ Generating photorealistic couture fitting and lighting...',
                '🎨 Blending fabric drapery, contours & pose fidelity...',
                '🪄 Finalizing high-resolution output from CatVTON model...',
                '⏳ Almost there — finishing rendering...',
            ];
            let tipIdx = 0;
            this.loadingTip = tips[0];
            const tipInterval = setInterval(() => {
                tipIdx = (tipIdx + 1) % tips.length;
                this.loadingTip = tips[tipIdx];
            }, 5000);

            try {
                const response = await fetch('/api/virtual-tryon/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        person_image: this.userImage,
                        garment_image: this.garmentImage,
                        category: this.category || 'upper_body',
                        product_name: this.productName
                    })
                });

                const data = await response.json();
                if (data.success && data.result_url) {
                    this.resultImage = data.result_url + '?t=' + Date.now();
                } else {
                    this.tryOnError = data.message || 'AI engine unavailable. Please retry.';
                    this.retryable = data.retryable === true;
                    if (this.retryable) {
                        this.retryCountdown = 30;
                        const cd = setInterval(() => {
                            this.retryCountdown--;
                            if (this.retryCountdown <= 0) clearInterval(cd);
                        }, 1000);
                    }
                }
            } catch (err) {
                console.error('Virtual try-on error:', err);
                this.tryOnError = 'Network error — check your connection and retry.';
                this.retryable = true;
                this.retryCountdown = 10;
                const cd = setInterval(() => { this.retryCountdown--; if (this.retryCountdown <= 0) clearInterval(cd); }, 1000);
            } finally {
                clearInterval(tipInterval);
                this.isLoading = false;
            }
        },

        addToCartFromTryOn() {
            if (Alpine.store('shop')) {
                Alpine.store('shop').addToCart({
                    id: this.productId,
                    name: this.productName,
                    price: this.productPrice,
                    image: this.garmentImage,
                    color: 'As Tried On',
                    size: 'M',
                    qty: 1
                });
                this.closeModal();
            }
        }
    };
}
window.virtualTryOn = virtualTryOn;
if (window.Alpine) {
    Alpine.data('virtualTryOn', virtualTryOn);
} else {
    document.addEventListener('alpine:init', () => {
        Alpine.data('virtualTryOn', virtualTryOn);
    });
}
</script>

<!-- Virtual Try-On Fitting Room Modal -->
<div x-data="virtualTryOn()" 
     x-show="isOpen" 
     x-cloak 
     style="display: none !important;"
     @open-tryon.window="openModal($event.detail)"
     @keydown.escape.window="closeModal()"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">

    <!-- Backdrop -->
    <div x-show="isOpen" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeModal()" 
         class="fixed inset-0 bg-black/75 backdrop-blur-md transition-opacity"></div>

    <div class="flex min-h-full items-center justify-center p-3 sm:p-6 text-center">
        <!-- Modal Card -->
        <div x-show="isOpen" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-4xl transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all border border-[var(--color-bisque)]/80 my-8">

            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[var(--color-champagne-light)] via-white to-[var(--color-champagne-light)] px-6 py-4 border-b border-[var(--color-bisque)]/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[var(--color-rose-antique)] to-[var(--color-rose-deep)] flex items-center justify-center text-white shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)] flex items-center gap-2">
                            AI Virtual Fitting Room
                            <span class="bg-[var(--color-rose-antique)]/15 text-[var(--color-rose-deep)] text-[10px] font-sans font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                🐈 CatVTON ICLR '25
                            </span>
                        </h2>
                        <p class="text-xs text-[var(--color-ebony)]/60 font-sans">Powered by official CatVTON concatenation diffusion architecture</p>
                    </div>
                </div>

                <button @click="closeModal()" class="w-9 h-9 rounded-full bg-black/5 hover:bg-black/10 flex items-center justify-center text-[var(--color-ebony)] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 sm:p-8 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    <!-- Left Column: Garment & Model Selection -->
                    <div class="lg:col-span-5 space-y-5">
                        
                        <!-- Selected Garment Card -->
                        <div class="bg-[var(--color-champagne-light)]/40 p-4 rounded-2xl border border-[var(--color-bisque)]/70 flex items-center gap-4">
                            <div class="w-16 h-20 rounded-xl overflow-hidden bg-white shadow-sm flex-shrink-0 border border-[var(--color-bisque)]">
                                <img :src="garmentImage" :alt="productName" class="w-full h-full object-cover object-top" />
                            </div>
                            <div class="overflow-hidden flex-1">
                                <span class="text-[10px] font-sans font-bold uppercase tracking-widest text-[var(--color-rose-antique)]">Selected Couture</span>
                                <h4 class="font-serif text-sm font-bold text-[var(--color-ebony)] truncate" x-text="productName"></h4>
                                <span class="text-xs font-sans font-bold text-[var(--color-ebony)]" x-text="'₹' + Number(productPrice).toLocaleString('en-IN')"></span>
                            </div>
                        </div>

                        <!-- Garment Category Selector -->
                        <div>
                            <label class="text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider block mb-1.5">Try-On Cloth Type</label>
                            <div class="grid grid-cols-3 gap-1.5 p-1 bg-gray-100 rounded-xl text-[11px] font-sans font-semibold text-center">
                                <button type="button" @click="category = 'upper_body'" :class="category === 'upper_body' ? 'bg-white text-[var(--color-rose-deep)] shadow-sm' : 'text-gray-500 hover:text-black'" class="py-1.5 rounded-lg transition-all">
                                    👗 Upper Body
                                </button>
                                <button type="button" @click="category = 'dresses'" :class="category === 'dresses' ? 'bg-white text-[var(--color-rose-deep)] shadow-sm' : 'text-gray-500 hover:text-black'" class="py-1.5 rounded-lg transition-all">
                                    ✨ Full Dress
                                </button>
                                <button type="button" @click="category = 'lower_body'" :class="category === 'lower_body' ? 'bg-white text-[var(--color-rose-deep)] shadow-sm' : 'text-gray-500 hover:text-black'" class="py-1.5 rounded-lg transition-all">
                                    👖 Lower Body
                                </button>
                            </div>
                        </div>

                        <!-- Choose Photo Source Tabs -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider">Choose Model Photo</label>
                                <span class="text-[11px] text-[var(--color-rose-antique)] font-semibold">Selfie or Model</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 p-1 bg-gray-100 rounded-xl mb-3 text-xs font-sans font-semibold">
                                <button type="button" @click="activeTab = 'models'" :class="activeTab === 'models' ? 'bg-white text-[var(--color-ebony)] shadow-sm' : 'text-gray-500 hover:text-black'" class="py-2 rounded-lg transition-all">
                                    Demo Models
                                </button>
                                <button type="button" @click="activeTab = 'upload'" :class="activeTab === 'upload' ? 'bg-white text-[var(--color-ebony)] shadow-sm' : 'text-gray-500 hover:text-black'" class="py-2 rounded-lg transition-all">
                                    Upload Photo
                                </button>
                                <button type="button" @click="activeTab = 'camera'; startCamera()" :class="activeTab === 'camera' ? 'bg-white text-[var(--color-ebony)] shadow-sm' : 'text-gray-500 hover:text-black'" class="py-2 rounded-lg transition-all">
                                    📷 Selfie
                                </button>
                            </div>

                            <!-- Status Bar: Currently selected photo indicator -->
                            <div x-show="userImage" class="flex items-center gap-2 mb-2 p-2 bg-green-50 border border-green-200 rounded-xl text-[11px] font-sans">
                                <img :src="userImage" class="w-8 h-10 rounded-lg object-cover border border-green-300 flex-shrink-0" />
                                <div class="flex-1 min-w-0">
                                    <span class="text-green-700 font-bold block">✔ Photo ready for try-on!</span>
                                    <span class="text-green-600 truncate block" x-text="selectedModel ? selectedModel.name : 'Your custom photo'"></span>
                                </div>
                                <button type="button" @click="userImage = null; selectedModel = null; resultImage = null" class="text-red-400 hover:text-red-600 text-[11px] font-bold flex-shrink-0">✕</button>
                            </div>

                            <!-- Tab 1: Demo Models -->
                            <div x-show="activeTab === 'models'" class="space-y-3">
                                <p class="text-[10px] text-gray-500 font-sans">Click a model below to instantly load them — then hit Generate!</p>
                                <div class="grid grid-cols-2 gap-2.5">
                                    <template x-for="model in demoModels" :key="model.id">
                                        <button type="button" @click="selectDemoModel(model)" :class="selectedModel?.id === model.id ? 'border-[var(--color-rose-antique)] ring-2 ring-[var(--color-rose-antique)]/20 bg-[var(--color-champagne-light)]/30' : 'border-[var(--color-bisque)] hover:border-gray-400'" class="p-2 rounded-2xl border text-left bg-white transition-all flex items-center gap-2.5">
                                            <img :src="model.image" :alt="model.name" class="w-12 h-14 rounded-xl object-cover" />
                                            <div class="overflow-hidden">
                                                <p class="text-[11px] font-bold text-[var(--color-ebony)] truncate" x-text="model.name.split(' ')[0]"></p>
                                                <p class="text-[10px] text-gray-500" x-text="model.height + ' • ' + model.size"></p>
                                                <span x-show="selectedModel?.id === model.id" class="text-[9px] text-[var(--color-rose-antique)] font-bold">✔ Selected</span>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Tab 2: Upload File -->
                            <div x-show="activeTab === 'upload'" class="space-y-2">
                                <!-- Show uploaded photo preview if already selected -->
                                <div x-show="userImage && !selectedModel" class="relative rounded-2xl overflow-hidden bg-gray-100 aspect-[3/4] max-h-52">
                                    <img :src="userImage" class="w-full h-full object-cover object-top" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end p-3">
                                        <span class="text-white text-[10px] font-bold">✔ Photo loaded — click Generate!</span>
                                    </div>
                                    <label class="absolute top-2 right-2 cursor-pointer bg-white/90 text-[var(--color-ebony)] text-[10px] font-bold px-2 py-1 rounded-full shadow">
                                        Change
                                        <input type="file" accept="image/*" @change="handleFileUpload($event)" class="hidden" />
                                    </label>
                                </div>

                                <!-- Upload zone when no photo yet -->
                                <label x-show="!userImage || selectedModel" class="border-2 border-dashed border-[var(--color-bisque)] hover:border-[var(--color-rose-antique)] rounded-2xl p-6 flex flex-col items-center justify-center cursor-pointer bg-[var(--color-champagne-light)]/20 transition-all text-center">
                                    <svg class="w-8 h-8 text-[var(--color-rose-antique)] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs font-sans font-bold text-[var(--color-ebony)]">Click to upload your full body photo</span>
                                    <span class="text-[10px] text-gray-500 mt-1">Best results: front-facing, full body, PNG/JPG</span>
                                    <input type="file" accept="image/*" @change="handleFileUpload($event)" class="hidden" />
                                </label>
                            </div>

                            <!-- Tab 3: Webcam Camera -->
                            <div x-show="activeTab === 'camera'" class="space-y-2">
                                <div class="relative rounded-2xl overflow-hidden bg-black aspect-[3/4] max-h-56 flex items-center justify-center">
                                    <video x-ref="videoElement" autoplay playsinline class="w-full h-full object-cover"></video>
                                    <button type="button" @click="capturePhoto()" class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-[var(--color-rose-antique)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg flex items-center gap-1.5 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Snap Photo
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button: Generate Try-On -->
                        <button type="button" 
                                @click="generateTryOn()" 
                                :disabled="!userImage || isLoading" 
                                class="w-full bg-gradient-to-r from-[var(--color-ebony)] to-[var(--color-rose-deep)] hover:opacity-95 text-white font-sans text-xs font-bold uppercase tracking-widest py-4 rounded-2xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <template x-if="!isLoading && !userImage">
                                <span class="flex items-center gap-2 opacity-60">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    Select a Photo First
                                </span>
                            </template>
                            <template x-if="!isLoading && userImage">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    ✨ Generate AI Try-On
                                </span>
                            </template>
                            <template x-if="isLoading">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Fitting Outfit — Please wait...
                                </span>
                            </template>
                        </button>

                    </div>

                    <!-- Right Column: Interactive Visualizer & Before/After Comparison -->
                    <div class="lg:col-span-7 space-y-4">
                        <div class="relative aspect-[3/4] w-full rounded-3xl overflow-hidden bg-gray-50 border border-[var(--color-bisque)]/80 flex items-center justify-center shadow-inner">
                            
                            <!-- State 1: Placeholder before user selects image -->
                            <div x-show="!userImage && !resultImage && !isLoading" class="text-center p-8 space-y-3">
                                <div class="w-16 h-16 rounded-full bg-[var(--color-champagne-light)] flex items-center justify-center mx-auto text-[var(--color-rose-antique)]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Select or Upload a Photo</h3>
                                <p class="text-xs text-gray-500 font-sans max-w-xs mx-auto">Pick one of our demo fashion models on the left or upload your own selfie to begin.</p>
                            </div>

                            <!-- State 2: User photo selected, ready to generate -->
                            <div x-show="userImage && !resultImage && !isLoading" class="w-full h-full relative">
                                <img :src="userImage" alt="Selected person" class="w-full h-full object-cover object-top" />
                                <div class="absolute bottom-4 left-4 right-4 bg-black/60 backdrop-blur-md text-white p-3 rounded-xl text-center text-xs font-sans">
                                    ✨ Ready! Click <strong>"Generate AI Try-On"</strong> to dress this model in <span x-text="productName"></span>
                                </div>
                            </div>

                            <!-- State 3: AI Loading Screen -->
                            <div x-show="isLoading" class="absolute inset-0 bg-black/80 backdrop-blur-sm flex flex-col items-center justify-center p-6 text-white text-center space-y-4 z-20">
                                <div class="relative w-20 h-20 flex items-center justify-center">
                                    <div class="absolute inset-0 rounded-full border-4 border-[var(--color-rose-antique)]/30 animate-ping"></div>
                                    <div class="w-16 h-16 rounded-full border-4 border-t-[var(--color-rose-antique)] border-white/20 animate-spin"></div>
                                    <svg class="w-8 h-8 text-amber-300 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-serif text-xl font-bold">Fitting Couture Outfit...</h4>
                                    <p class="text-xs text-gray-300 font-sans mt-1 max-w-xs" x-text="loadingTip"></p>
                                </div>
                                <div class="w-48 bg-white/20 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-[var(--color-rose-antique)] h-full w-2/3 animate-pulse"></div>
                                </div>
                            </div>

                            <!-- State 3b: Error / Retry Panel -->
                            <div x-show="tryOnError && !isLoading && !resultImage" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center space-y-4 bg-gray-900/95">
                                <div class="w-14 h-14 rounded-full bg-amber-500/20 border border-amber-400/40 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <div class="text-white">
                                    <h4 class="font-serif text-base font-bold mb-1">AI Engine Waking Up</h4>
                                    <p class="text-xs text-gray-300 max-w-[200px] mx-auto" x-text="tryOnError"></p>
                                </div>
                                <!-- Retry countdown -->
                                <div x-show="retryCountdown > 0" class="text-xs text-amber-300 font-mono">Retry in <span x-text="retryCountdown"></span>s...</div>
                                <button x-show="retryCountdown <= 0" type="button" @click="generateTryOn()" class="bg-[var(--color-rose-antique)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-bold px-5 py-2.5 rounded-full flex items-center gap-2 transition-all shadow-lg">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Retry AI Try-On
                                </button>
                                <p class="text-[10px] text-gray-400 max-w-[220px] mx-auto">💡 Add SEGMIND_API_KEY (100 free/day) or HUGGINGFACE_API_KEY in .env for dedicated cloud AI</p>
                            </div>

                            <!-- State 4: Result Display with View Modes & Interactive Split Slider -->
                            <div x-show="resultImage && !isLoading" class="w-full h-full relative select-none" x-data="{ viewMode: 'split' }">
                                
                                <!-- Mode 1: Split Comparison -->
                                <div x-show="viewMode === 'split'" class="w-full h-full relative">
                                    <!-- Result Image (After) -->
                                    <img :src="resultImage" alt="AI Try-On Result" class="w-full h-full object-cover object-top" />

                                    <!-- Original Image (Before) clipped by slider -->
                                    <div class="absolute inset-0 overflow-hidden" :style="'width: ' + sliderPos + '%'">
                                        <img :src="userImage" alt="Before" class="w-full h-full object-cover object-top max-w-none" :style="'width: ' + ($refs.resultBox ? $refs.resultBox.clientWidth + 'px' : '100%')" />
                                    </div>

                                    <!-- Slider Divider Bar & Handle -->
                                    <div class="absolute top-0 bottom-0 w-1 bg-white shadow-2xl cursor-ew-resize flex items-center justify-center pointer-events-none" :style="'left: ' + sliderPos + '%'">
                                        <div class="w-8 h-8 rounded-full bg-[var(--color-ebony)] text-white border-2 border-white shadow-lg flex items-center justify-center text-[10px] font-bold">
                                            ↔
                                        </div>
                                    </div>

                                    <!-- Slider Input -->
                                    <input type="range" min="0" max="100" x-model="sliderPos" class="absolute inset-0 opacity-0 cursor-ew-resize w-full h-full z-10" />

                                    <!-- Badges -->
                                    <span class="absolute top-4 left-4 bg-black/70 text-white text-[10px] font-sans font-bold px-2.5 py-1 rounded-full backdrop-blur-md pointer-events-none">BEFORE</span>
                                    <span class="absolute top-4 right-4 bg-[var(--color-rose-deep)] text-white text-[10px] font-sans font-bold px-2.5 py-1 rounded-full backdrop-blur-md pointer-events-none">AI TRY-ON</span>
                                </div>

                                <!-- Mode 2: 100% Full AI Result -->
                                <div x-show="viewMode === 'after'" class="w-full h-full relative">
                                    <img :src="resultImage" alt="AI Try-On Full Look" class="w-full h-full object-cover object-top" />
                                    <span class="absolute top-4 right-4 bg-[var(--color-rose-deep)] text-white text-[10px] font-sans font-bold px-3 py-1 rounded-full shadow-lg backdrop-blur-md">✨ DRESSED IN ESTILO COUTURE</span>
                                </div>

                                <!-- Mode 3: 100% Original Photo -->
                                <div x-show="viewMode === 'before'" class="w-full h-full relative">
                                    <img :src="userImage" alt="Original Photo" class="w-full h-full object-cover object-top" />
                                    <span class="absolute top-4 left-4 bg-black/70 text-white text-[10px] font-sans font-bold px-3 py-1 rounded-full backdrop-blur-md">ORIGINAL PHOTO</span>
                                </div>

                                <!-- Quick View Mode Selector Pill -->
                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 bg-black/75 backdrop-blur-md p-1 rounded-full flex items-center gap-1 text-[10px] font-sans font-bold text-white shadow-xl">
                                    <button type="button" @click="viewMode = 'split'" :class="viewMode === 'split' ? 'bg-[var(--color-rose-antique)] text-white' : 'text-gray-300 hover:text-white'" class="px-3 py-1 rounded-full transition-all">Split Slider ↔</button>
                                    <button type="button" @click="viewMode = 'after'" :class="viewMode === 'after' ? 'bg-[var(--color-rose-antique)] text-white' : 'text-gray-300 hover:text-white'" class="px-3 py-1 rounded-full transition-all">AI Outfit Only ✨</button>
                                    <button type="button" @click="viewMode = 'before'" :class="viewMode === 'before' ? 'bg-[var(--color-rose-antique)] text-white' : 'text-gray-300 hover:text-white'" class="px-3 py-1 rounded-full transition-all">Original 📷</button>
                                </div>

                            </div>

                        </div>

                        <!-- Result Actions (Download & Add to Bag) -->
                        <div x-show="resultImage && !isLoading" class="flex items-center gap-3 pt-2">
                            <a :href="resultImage" download="estilo-virtual-tryon.jpg" class="flex-1 border border-[var(--color-bisque)] hover:bg-gray-50 text-[var(--color-ebony)] font-sans text-xs font-bold uppercase tracking-wider py-3.5 rounded-xl text-center flex items-center justify-center gap-2 transition-all shadow-sm">
                                <svg class="w-4 h-4 text-[var(--color-rose-antique)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg> Save Outfit
                            </a>

                            <button type="button" @click="addToCartFromTryOn()" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-wider py-3.5 rounded-xl shadow-md flex items-center justify-center gap-2 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg> Add Outfit to Bag
                            </button>
                        </div>
                    </div>

                </div>
            </div>


            <!-- Modal Footer Note -->
            <div class="bg-gray-50 px-6 py-3 border-t border-[var(--color-bisque)]/40 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-gray-500 font-sans">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    100% Free AI Virtual Try-On Powered by Estilo AI & Open Diffusion
                </span>
                <span>Drag slider ↔ to compare before and after</span>
            </div>

        </div>
    </div>
</div>
