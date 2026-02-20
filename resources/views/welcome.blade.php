<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HFSTUDIOS - Acceso Exclusivo</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Helvetica Neue', sans-serif;
        }
        
        [x-cloak] { display: none !important; }
        
        html { scroll-behavior: smooth; }
        
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        
        .marquee-content {
            animation: marquee 20s linear infinite;
        }
        
        .product-card:hover .hover-overlay {
            opacity: 1;
            transform: translateY(0);
        }
        
        .hover-overlay {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        
        .grayscale-map {
            filter: grayscale(100%);
        }
    </style>
</head>
<body class="bg-white antialiased" x-data="app()" x-init="init()">
    
    <!-- Toast Notification -->
    <div 
        x-show="toast.show"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-x-full"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-full"
        class="fixed top-6 right-6 z-[90] bg-black text-white px-6 py-4 shadow-2xl max-w-sm"
        x-cloak>
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <p class="font-semibold text-sm" x-text="toast.message"></p>
        </div>
    </div>
    
    <!-- Product Detail Modal -->
    <div 
        x-show="productDetailOpen"
        x-cloak
        class="fixed inset-0 z-[85] overflow-y-auto">
        
        <div 
            @click="productDetailOpen = false"
            x-show="productDetailOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/80"></div>
        
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div 
                x-show="productDetailOpen"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.stop
                class="relative bg-white max-w-6xl w-full grid md:grid-cols-2 gap-0 shadow-2xl">
                
                <button 
                    @click="productDetailOpen = false"
                    class="absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center bg-white/90 hover:bg-white rounded-full text-black font-bold text-2xl shadow-lg">
                    ×
                </button>
                
                <div class="bg-[#F4F4F4] flex items-center justify-center p-8 min-h-[500px]">
                    <img 
                        :src="selectedProduct.image" 
                        :alt="selectedProduct.name"
                        class="w-full h-auto object-cover max-h-[600px]">
                </div>
                
                <div class="p-8 md:p-12 flex flex-col justify-center">
                    <span 
                        x-show="selectedProduct.badge"
                        class="inline-block bg-black text-white text-[9px] font-bold uppercase tracking-wider px-2 py-1 mb-4 w-fit">
                        <span x-text="selectedProduct.badge"></span>
                    </span>
                    
                    <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight mb-3" x-text="selectedProduct.name"></h2>
                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-4" x-text="selectedProduct.color"></p>
                    <p class="text-3xl font-bold mb-6" x-text="'$' + selectedProduct.price + ' MXN'"></p>
                    
                    <p class="text-sm text-gray-700 leading-relaxed mb-8" x-text="selectedProduct.description"></p>
                    
                    <div class="space-y-4">
                        <button 
                            @click="addToCart(selectedProduct); productDetailOpen = false"
                            class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                            Agregar al Carrito
                        </button>
                        
                        <button 
                            @click="productDetailOpen = false"
                            class="w-full border-2 border-black text-black py-4 text-sm font-bold uppercase tracking-widest hover:bg-black hover:text-white transition">
                            Regresar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Success Modal -->
    <div 
        x-show="orderSuccessOpen"
        x-cloak
        class="fixed inset-0 z-[80] flex items-center justify-center p-4">
        
        <div 
            @click="orderSuccessOpen = false"
            x-show="orderSuccessOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/70"></div>
        
        <div 
            x-show="orderSuccessOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white max-w-md w-full p-12 text-center shadow-2xl">
            
            <div class="w-24 h-24 mx-auto mb-6 bg-green-500 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h2 class="text-3xl font-black uppercase tracking-tight mb-3">¡Compra Confirmada!</h2>
            <p class="text-gray-600 mb-2">Gracias por tu compra</p>
            <p class="text-sm font-mono bg-gray-100 inline-block px-4 py-2 mb-8" x-text="orderNumber"></p>
            
            <button 
                @click="orderSuccessOpen = false; navigateTo('catalogo')"
                class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                Continuar Comprando
            </button>
        </div>
    </div>
    
    <!-- Top Marquee Bar (Hidden on inicio) -->
    <div x-show="currentRoute !== 'inicio'" class="bg-[#F0EBE0] overflow-hidden relative h-6">
        <div class="flex whitespace-nowrap marquee-content">
            <span class="inline-block text-[10px] font-bold uppercase tracking-[0.2em] py-1.5 px-8">
                ENVÍO GRATIS EN TODOS LOS PEDIDOS | © HF STUDIOS, 2026
            </span>
            <span class="inline-block text-[10px] font-bold uppercase tracking-[0.2em] py-1.5 px-8">
                ENVÍO GRATIS EN TODOS LOS PEDIDOS | © HF STUDIOS, 2026
            </span>
            <span class="inline-block text-[10px] font-bold uppercase tracking-[0.2em] py-1.5 px-8">
                ENVÍO GRATIS EN TODOS LOS PEDIDOS | © HF STUDIOS, 2026
            </span>
            <span class="inline-block text-[10px] font-bold uppercase tracking-[0.2em] py-1.5 px-8">
                ENVÍO GRATIS EN TODOS LOS PEDIDOS | © HF STUDIOS, 2026
            </span>
        </div>
    </div>
    
    <!-- Global Navigation (Hidden on inicio) -->
    <nav x-show="currentRoute !== 'inicio'" class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-screen-2xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="hidden md:flex items-center gap-8">
                    <button @click="navigateTo('catalogo')" class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition">Catálogo</button>
                    <button @click="navigateTo('nosotros')" class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition">Nosotros</button>
                    <button @click="navigateTo('contacto')" class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition">Contacto</button>
                </div>
                
                <div class="absolute left-1/2 transform -translate-x-1/2">
                    <button @click="navigateTo('catalogo')" class="text-2xl md:text-3xl font-black tracking-tighter">HFSTUDIOS</button>
                </div>
                
                <div class="hidden md:flex items-center gap-6">
                    <button 
                        x-show="!isLoggedIn"
                        @click="navigateTo('login')"
                        class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition">
                        Iniciar sesión
                    </button>
                    <button 
                        x-show="isLoggedIn"
                        @click="navigateTo('perfil')"
                        class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition">
                        Perfil
                    </button>
                    <button 
                        @click="cartOpen = true" 
                        class="text-xs font-bold uppercase tracking-wider hover:opacity-60 transition relative">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Carrito (<span x-text="cartCount"></span>)
                        <span x-show="cartCount > 0" class="absolute -top-1 -right-1 w-2 h-2 bg-black rounded-full animate-pulse"></span>
                    </button>
                </div>
                
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-sm font-bold">
                    <span x-show="!mobileMenuOpen">MENÚ</span>
                    <span x-show="mobileMenuOpen">CERRAR</span>
                </button>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Menu -->
    <div 
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black z-40 md:hidden"
        x-cloak>
        <div class="flex flex-col items-center justify-center h-full space-y-8 text-white">
            <button @click="navigateTo('catalogo'); mobileMenuOpen = false" class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">Catálogo</button>
            <button @click="navigateTo('nosotros'); mobileMenuOpen = false" class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">Nosotros</button>
            <button @click="navigateTo('contacto'); mobileMenuOpen = false" class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">Contacto</button>
            <button 
                x-show="!isLoggedIn"
                @click="navigateTo('login'); mobileMenuOpen = false" 
                class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">
                Iniciar sesión
            </button>
            <button 
                x-show="isLoggedIn"
                @click="navigateTo('perfil'); mobileMenuOpen = false" 
                class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">
                Perfil
            </button>
            <button @click="cartOpen = true; mobileMenuOpen = false" class="text-3xl font-bold uppercase tracking-wider hover:opacity-60 transition">
                Carrito (<span x-text="cartCount"></span>)
            </button>
        </div>
    </div>
    
    <!-- Cart Drawer -->
    <div x-show="cartOpen" class="fixed inset-0 z-[60]" x-cloak>
        <div 
            @click="cartOpen = false"
            x-show="cartOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/70"></div>
        
        <div 
            x-show="cartOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col">
            
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-2xl font-black uppercase tracking-tight">Tu Carrito</h2>
                <button @click="cartOpen = false" class="text-3xl font-bold hover:opacity-60">&times;</button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6 custom-scroll">
                <template x-if="cart.length === 0">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p class="text-gray-500 text-sm uppercase tracking-wider mb-4 font-semibold">Tu carrito está vacío</p>
                        <button @click="cartOpen = false" class="text-xs font-bold uppercase tracking-wider underline hover:no-underline">Continuar Comprando</button>
                    </div>
                </template>
                
                <div class="space-y-6">
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="flex gap-4 pb-6 border-b border-gray-200">
                            <img :src="item.image" :alt="item.name" class="w-24 h-32 object-cover bg-gray-100 flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-bold uppercase tracking-wide mb-1 truncate" x-text="item.name"></h3>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2" x-text="item.color"></p>
                                <p class="text-base font-bold" x-text="'$' + item.price.toLocaleString() + ' MXN'"></p>
                            </div>
                            <button 
                                @click="removeFromCart(index)" 
                                class="text-xs text-gray-400 hover:text-red-600 font-semibold transition self-start">
                                Eliminar
                            </button>
                        </div>
                    </template>
                </div>
            </div>
            
            <div class="border-t border-gray-200 p-6 space-y-4 bg-gray-50" x-show="cart.length > 0">
                <div class="flex items-center justify-between text-lg font-bold">
                    <span class="uppercase tracking-wider">Subtotal</span>
                    <span x-text="'$' + cartTotal.toLocaleString() + ' MXN'"></span>
                </div>
                <button 
                    @click="handleCheckout()"
                    :disabled="isCheckingOut"
                    class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition disabled:opacity-50">
                    <span x-text="isCheckingOut ? 'Procesando...' : 'Finalizar Compra'"></span>
                </button>
                <button @click="cartOpen = false" class="w-full text-sm font-bold uppercase tracking-wider underline hover:no-underline">
                    Continuar Comprando
                </button>
            </div>
        </div>
    </div>
    
    <!-- ROUTE: Inicio (Netflix-Style Landing Page) -->
    <div x-show="currentRoute === 'inicio'" x-cloak>
        <section class="relative h-screen overflow-hidden">
            <!-- Background Image with Dark Overlay -->
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1558769132-cb1aea3c4e86?q=80&w=2400&auto=format&fit=crop" 
                     alt="SS26 Drop" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/70 to-black/90"></div>
            </div>
            
            <!-- Top Bar (Transparent) -->
            <div class="relative z-10 flex items-center justify-between px-6 md:px-12 py-6">
                <h1 class="text-2xl md:text-3xl font-black tracking-tighter text-white">HFSTUDIOS</h1>
                <button 
                    @click="navigateTo('login')"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 text-sm font-bold uppercase tracking-wider transition">
                    Iniciar sesión
                </button>
            </div>
            
            <!-- Center Content (Netflix-Style) -->
            <div class="relative z-10 flex flex-col items-center justify-center h-full px-6 pb-32">
                <div class="max-w-3xl text-center">
                    <h2 class="text-4xl md:text-6xl lg:text-7xl font-black uppercase tracking-tight text-white mb-6 leading-tight">
                        ACCESO EXCLUSIVO<br>AL DROP SS26.
                    </h2>
                    
                    <p class="text-lg md:text-xl text-gray-300 mb-12 font-medium">
                        Streetwear premium. Ingresa tu correo para descubrir el catálogo.
                    </p>
                    
                    <!-- The Netflix-Style Form -->
                    <form @submit.prevent="handleLandingSubmit()" novalidate class="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto">
                        <input 
                            type="text" 
                            x-model="landingEmail"
                            placeholder="Email"
                            class="flex-1 px-6 py-4 text-lg bg-white/90 border-2 border-white/20 focus:border-white focus:outline-none text-black">
                        
                        <button 
                            type="submit"
                            class="bg-white hover:bg-gray-200 text-black px-8 py-4 text-lg font-black uppercase tracking-widest transition whitespace-nowrap">
                            Comenzar <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    
    <!-- ROUTE: Nosotros -->
    <div x-show="currentRoute === 'nosotros'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-8">Sobre Nosotros</h1>
                
                <div class="space-y-6 text-gray-700 leading-relaxed mb-12">
                    <p class="text-xl font-semibold">
                        Fundada en 2026, HFSTUDIOS representa la intersección del lujo y la cultura urbana. 
                        Creamos piezas premium que honran el pasado mientras empujan los límites hacia el futuro.
                    </p>
                    
                    <h2 class="text-2xl font-black uppercase tracking-tight mt-12 mb-4">Nuestra Misión</h2>
                    <p>
                        Ofrecemos streetwear de alta calidad diseñado para individuos que se niegan a mezclarse con la multitud. 
                        Cada prenda es una declaración de autenticidad y estilo.
                    </p>
                    
                    <h2 class="text-2xl font-black uppercase tracking-tight mt-12 mb-4">Horario de Tienda</h2>
                    <div class="bg-[#F4F4F4] p-8 space-y-2">
                        <p class="text-lg font-semibold">Lunes a Sábado: 10:00 - 20:00</p>
                        <p class="text-lg font-semibold">Domingo: 12:00 - 18:00</p>
                    </div>
                </div>
                
                <div class="mt-12">
                    <h2 class="text-2xl font-black uppercase tracking-tight mb-4">Nuestra Ubicación</h2>
                    <p class="text-lg font-semibold mb-4">Aguascalientes, México</p>
                    <div class="aspect-video rounded overflow-hidden border-2 border-gray-200">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d119237.80976866765!2d-102.36291868828125!3d21.88089!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8429ee8436a52d4f%3A0x4f8d5b8c3f3c5f7d!2sAguascalientes%2C%20Ags.%2C%20Mexico!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus"
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="grayscale-map">
                        </iframe>
                    </div>
                </div>
                
                <div class="mt-12 text-center">
                    <button @click="navigateTo('catalogo')" class="inline-block bg-black text-white px-8 py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        Ver Colección
                    </button>
                </div>
            </div>
        </section>
    </div>
    
    <!-- ROUTE: Contacto -->
    <div x-show="currentRoute === 'contacto'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="max-w-2xl mx-auto">
                <h1 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-8">Contacto</h1>
                
                <form @submit.prevent="handleContactSubmit()" novalidate class="space-y-6 mb-12">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Nombre</label>
                        <input 
                            type="text" 
                            x-model="contactForm.name"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="Tu nombre">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Email</label>
                        <input 
                            type="text" 
                            x-model="contactForm.email"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="tu@email.com">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Mensaje</label>
                        <textarea 
                            x-model="contactForm.message"
                            rows="5"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm resize-none"
                            placeholder="Tu mensaje..."></textarea>
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        Enviar Mensaje
                    </button>
                </form>
                
                <div class="text-center">
                    <p class="text-xs font-bold uppercase tracking-wider mb-4 text-gray-600">Síguenos en</p>
                    <div class="flex justify-center gap-8">
                        <a href="#" class="text-4xl hover:opacity-60 transition" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-4xl hover:opacity-60 transition" title="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <!-- ROUTE: Catálogo -->
    <div x-show="currentRoute === 'catalogo'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="mb-12">
                <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight mb-2">Catálogo</h2>
                <p class="text-sm text-gray-600 uppercase tracking-wider mb-8">Nuestras Piezas Más Vendidas</p>
                
                <div class="flex flex-wrap gap-3">
                    <button 
                        @click="selectedCategory = 'All'"
                        :class="selectedCategory === 'All' ? 'bg-black text-white' : 'bg-white text-black border border-gray-300 hover:border-black'"
                        class="px-6 py-2 text-xs font-bold uppercase tracking-wider transition">
                        All
                    </button>
                    <button 
                        @click="selectedCategory = 'Hoodies'"
                        :class="selectedCategory === 'Hoodies' ? 'bg-black text-white' : 'bg-white text-black border border-gray-300 hover:border-black'"
                        class="px-6 py-2 text-xs font-bold uppercase tracking-wider transition">
                        Hoodies
                    </button>
                    <button 
                        @click="selectedCategory = 'Tees'"
                        :class="selectedCategory === 'Tees' ? 'bg-black text-white' : 'bg-white text-black border border-gray-300 hover:border-black'"
                        class="px-6 py-2 text-xs font-bold uppercase tracking-wider transition">
                        Tees
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="product-card group cursor-pointer" @click="viewProductDetail(product)">
                        <div class="relative bg-[#F4F4F4] overflow-hidden mb-4">
                            <span 
                                x-show="product.badge"
                                class="absolute top-3 left-3 bg-white text-black text-[9px] font-bold uppercase tracking-wider px-2 py-1 z-10"
                                x-text="product.badge"></span>
                            <img 
                                :src="product.image" 
                                :alt="product.name"
                                class="w-full aspect-[3/4] object-cover group-hover:scale-105 transition duration-500">
                            
                            <div class="hover-overlay absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-full px-6 py-3 shadow-lg">
                                <p class="text-xs font-bold uppercase">Ver Detalle</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wide mb-1" x-text="product.name"></h3>
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2" x-text="product.color"></p>
                            <p class="text-sm font-bold" x-text="'$' + product.price.toLocaleString() + ' MXN'"></p>
                        </div>
                    </div>
                </template>
            </div>
        </section>
    </div>
    
    <!-- ROUTE: Registro -->
    <div x-show="currentRoute === 'registro'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="max-w-md mx-auto bg-white p-10 shadow-2xl">
                <h1 class="text-3xl font-black uppercase tracking-tight mb-8 text-center">Crear Cuenta</h1>
                
                <form @submit.prevent="handleRegistro()" novalidate class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Nombre</label>
                        <input 
                            type="text" 
                            x-model="registroForm.name"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="Tu nombre">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Email</label>
                        <input 
                            type="text" 
                            x-model="registroForm.email"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="tu@email.com">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Contraseña</label>
                        <input 
                            type="password" 
                            x-model="registroForm.password"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="••••••••">
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        Registrarme
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        ¿Ya tienes cuenta? 
                        <button @click="navigateTo('login')" class="font-bold underline hover:no-underline">Inicia sesión</button>
                    </p>
                </div>
            </div>
        </section>
    </div>
    
    <!-- ROUTE: Login -->
    <div x-show="currentRoute === 'login'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="max-w-md mx-auto bg-white p-10 shadow-2xl">
                <h1 class="text-3xl font-black uppercase tracking-tight mb-8 text-center">Iniciar Sesión</h1>
                
                <form @submit.prevent="handleLogin()" novalidate class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Email</label>
                        <input 
                            type="text" 
                            x-model="loginForm.email"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="tu@email.com">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-gray-600">Contraseña</label>
                        <input 
                            type="password" 
                            x-model="loginForm.password"
                            class="w-full px-4 py-3 border-2 border-gray-200 focus:border-black focus:outline-none text-sm"
                            placeholder="••••••••">
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-black text-white py-4 text-sm font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        Entrar
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        ¿No tienes cuenta? 
                        <button @click="navigateTo('registro')" class="font-bold underline hover:no-underline">Regístrate</button>
                    </p>
                </div>
            </div>
        </section>
    </div>
    
    <!-- ROUTE: Perfil -->
    <div x-show="currentRoute === 'perfil'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tight mb-8">Mi Perfil</h1>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-gray-50 p-8">
                        <h2 class="text-xl font-black uppercase tracking-tight mb-6">Mis Datos</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Nombre</p>
                                <p class="text-lg font-semibold" x-text="user.name"></p>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Email</p>
                                <p class="text-lg font-semibold" x-text="user.email"></p>
                            </div>
                        </div>
                        <button @click="handleLogout()" class="mt-6 w-full bg-black text-white py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                            Cerrar Sesión
                        </button>
                    </div>
                    
                    <div class="bg-gray-50 p-8">
                        <h2 class="text-xl font-black uppercase tracking-tight mb-6">Mis Compras</h2>
                        <p class="text-gray-600 text-sm">(Próximamente)</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <!-- ROUTE: Admin Panel -->
    <div x-show="currentRoute === 'admin'" x-cloak>
        <section class="max-w-screen-2xl mx-auto px-6 py-20">
            <div class="mb-8 flex items-center justify-between">
                <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tight">Panel Administrativo</h1>
                <button @click="navigateTo('catalogo')" class="text-xs font-bold uppercase tracking-wider underline hover:no-underline">
                    Volver a la Tienda
                </button>
            </div>
            
            <div class="bg-gray-50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-black uppercase tracking-tight">Gestión de Productos</h2>
                    <button class="bg-black text-white px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition">
                        + Agregar Producto
                    </button>
                </div>
                
                <div class="bg-white border border-gray-200 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Producto</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Categoría</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-700">Precio</th>
                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-700">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="product in allProducts" :key="product.id">
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <img :src="product.image" :alt="product.name" class="w-16 h-20 object-cover bg-gray-100">
                                            <div>
                                                <p class="text-sm font-bold" x-text="product.name"></p>
                                                <p class="text-xs text-gray-500" x-text="product.color"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-semibold px-2 py-1 bg-gray-100 rounded" x-text="product.category"></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold" x-text="'$' + product.price + ' MXN'"></p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="text-xs font-bold text-blue-600 hover:underline mr-3">Editar</button>
                                        <button class="text-xs font-bold text-red-600 hover:underline">Eliminar</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    
    <!-- Footer (Hidden on inicio) -->
    <footer x-show="currentRoute !== 'inicio'" class="bg-black text-white py-16">
        <div class="max-w-screen-2xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div>
                    <h3 class="text-2xl font-black mb-4 tracking-tighter">HFSTUDIOS</h3>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Streetwear premium para quienes se atreven a ser diferentes.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-4">Tienda</h4>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li><button @click="navigateTo('catalogo')" class="hover:text-white transition">Nuevos Ingresos</button></li>
                        <li><button @click="navigateTo('catalogo')" class="hover:text-white transition">Más Vendidos</button></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-4">Empresa</h4>
                    <ul class="space-y-2 text-xs text-gray-400">
                        <li><button @click="navigateTo('nosotros')" class="hover:text-white transition">Sobre Nosotros</button></li>
                        <li><button @click="navigateTo('contacto')" class="hover:text-white transition">Contacto</button></li>
                        <li><button @click="navigateTo('admin')" class="hover:text-white transition">Admin</button></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider mb-4">Newsletter</h4>
                    <p class="text-xs text-gray-400 mb-4">Suscríbete para ofertas exclusivas.</p>
                    <div class="flex">
                        <input 
                            type="text" 
                            placeholder="Tu email" 
                            class="flex-1 bg-white/10 border border-white/20 px-4 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-white">
                        <button class="bg-white text-black px-6 py-2 text-xs font-bold hover:bg-gray-200 transition">
                            →
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10 pt-8 text-center">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider">© 2026 HF Studios. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    
    <!-- Alpine.js App Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                currentRoute: 'inicio',
                landingEmail: '',
                
                allProducts: [
                    { id: 1, name: 'Hoodie Esencial', color: 'Gris Carbón', price: 1890, image: 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?q=80&w=800&auto=format&fit=crop', badge: 'Restock', category: 'Hoodies', description: 'Hoodie premium de alta calidad con ajuste relajado y diseño minimalista.' },
                    { id: 2, name: 'Tee Oversized', color: 'Blanco Hueso', price: 1490, image: 'https://images.unsplash.com/photo-1622445275576-721325763afe?q=80&w=800&auto=format&fit=crop', badge: null, category: 'Tees', description: 'Camiseta oversized de algodón 100% con corte holgado y cómodo.' },
                    { id: 3, name: 'Hoodie Gráfico', color: 'Negro Lavado', price: 2090, image: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?q=80&w=800&auto=format&fit=crop', badge: 'Nuevo', category: 'Hoodies', description: 'Hoodie con gráficos exclusivos y acabado lavado para look desgastado.' },
                    { id: 4, name: 'Tee Vintage', color: 'Gris', price: 1390, image: 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?q=80&w=800&auto=format&fit=crop', badge: null, category: 'Tees', description: 'Camiseta con lavado vintage y gráfico retro exclusivo.' },
                    { id: 5, name: 'Hoodie Premium', color: 'Negro', price: 2290, image: 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?q=80&w=800&auto=format&fit=crop', badge: 'Nuevo', category: 'Hoodies', description: 'Hoodie de peso pesado con construcción premium y detalles bordados.' },
                    { id: 6, name: 'Tee Básico', color: 'Blanco', price: 990, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=800&auto=format&fit=crop', badge: null, category: 'Tees', description: 'Camiseta básica de algodón peinado con ajuste regular.' },
                    { id: 7, name: 'Hoodie Zip', color: 'Gris Pizarra', price: 2490, image: 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=800&auto=format&fit=crop', badge: 'Restock', category: 'Hoodies', description: 'Hoodie con zipper completo y bolsillos laterales funcionales.' },
                    { id: 8, name: 'Tee Pocket', color: 'Negro', price: 1190, image: 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?q=80&w=800&auto=format&fit=crop', badge: null, category: 'Tees', description: 'Camiseta con bolsillo frontal y construcción reforzada.' }
                ],
                
                selectedCategory: 'All',
                selectedProduct: {},
                productDetailOpen: false,
                cart: [],
                cartOpen: false,
                mobileMenuOpen: false,
                orderSuccessOpen: false,
                isCheckingOut: false,
                orderNumber: '',
                
                isLoggedIn: false,
                user: { name: '', email: '' },
                
                registroForm: { name: '', email: '', password: '' },
                loginForm: { email: '', password: '' },
                contactForm: { name: '', email: '', message: '' },
                
                toast: { show: false, message: '' },
                
                init() {
                    const savedCart = localStorage.getItem('hfstudios_cart');
                    if (savedCart) this.cart = JSON.parse(savedCart);
                    
                    const savedUser = localStorage.getItem('hfstudios_user');
                    if (savedUser) {
                        this.user = JSON.parse(savedUser);
                        this.isLoggedIn = true;
                    }
                    
                    this.$watch('cart', value => {
                        localStorage.setItem('hfstudios_cart', JSON.stringify(value));
                    });
                },
                
                get filteredProducts() {
                    if (this.selectedCategory === 'All') return this.allProducts;
                    return this.allProducts.filter(p => p.category === this.selectedCategory);
                },
                
                get cartCount() {
                    return this.cart.length;
                },
                
                get cartTotal() {
                    return this.cart.reduce((total, item) => total + item.price, 0);
                },
                
                navigateTo(route) {
                    this.currentRoute = route;
                    window.scrollTo(0, 0);
                },
                
                showToast(message) {
                    this.toast.message = message;
                    this.toast.show = true;
                    setTimeout(() => this.toast.show = false, 3000);
                },
                
                handleLandingSubmit() {
                    this.navigateTo('catalogo');
                },
                
                viewProductDetail(product) {
                    this.selectedProduct = product;
                    this.productDetailOpen = true;
                },
                
                addToCart(product) {
                    this.cart.push({
                        id: Date.now(),
                        name: product.name,
                        color: product.color,
                        price: product.price,
                        image: product.image
                    });
                    this.showToast('Producto agregado al carrito');
                    this.cartOpen = true;
                },
                
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },
                
                async handleCheckout() {
                    if (this.cart.length === 0) return;
                    
                    this.isCheckingOut = true;
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    
                    this.orderNumber = 'HF-' + Math.random().toString(36).substr(2, 9).toUpperCase();
                    this.cart = [];
                    this.isCheckingOut = false;
                    this.cartOpen = false;
                    this.orderSuccessOpen = true;
                },
                
                handleContactSubmit() {
                    this.showToast('Mensaje enviado (simulado)');
                    this.contactForm = { name: '', email: '', message: '' };
                },
                
                handleRegistro() {
                    this.showToast('Registro exitoso (simulado)');
                    this.registroForm = { name: '', email: '', password: '' };
                    this.navigateTo('login');
                },
                
                handleLogin() {
                    this.user.name = this.loginForm.email || 'Usuario';
                    this.user.email = this.loginForm.email;
                    this.isLoggedIn = true;
                    
                    localStorage.setItem('hfstudios_user', JSON.stringify(this.user));
                    
                    this.showToast('Sesión iniciada (simulado)');
                    this.loginForm = { email: '', password: '' };
                    this.navigateTo('perfil');
                },
                
                handleLogout() {
                    this.isLoggedIn = false;
                    this.user = { name: '', email: '' };
                    localStorage.removeItem('hfstudios_user');
                    this.showToast('Sesión cerrada (simulado)');
                    this.navigateTo('inicio');
                }
            }));
        });
    </script>
    
</body>
</html>