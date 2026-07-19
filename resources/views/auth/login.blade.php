<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Masuk - Toko Jadi AgroFlow POS</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&amp;family=Hanken+Grotesk:wght@600;700;800&amp;family=JetBrains+Mono:wght@500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Shared Components Logic:
         This is a Transactional/Focused screen (Login), 
         so TopNavBar, SideNavBar, and BottomNavBar are suppressed 
         to prioritize the content canvas per the Semantic Shell Mandate. 
    -->
<style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .font-headline { font-family: 'Hanken Grotesk', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }
        
        /* Subtle Gradient Background as requested */
        .login-bg {
            background: linear-gradient(135deg, #f8f9fa 0%, #e8f5e9 100%);
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "tertiary-fixed": "#ffd9e2",
                      "surface": "#f8f9fa",
                      "error": "#ba1a1a",
                      "on-surface": "#191c1d",
                      "inverse-on-surface": "#f0f1f2",
                      "primary": "#0d631b",
                      "surface-container-high": "#e7e8e9",
                      "outline-variant": "#bfcaba",
                      "on-tertiary-fixed": "#3f001c",
                      "secondary-fixed": "#acf4a4",
                      "text-secondary": "#5F635F",
                      "tertiary-container": "#b14b6f",
                      "on-error": "#ffffff",
                      "surface-container-lowest": "#ffffff",
                      "on-secondary-fixed": "#002203",
                      "danger-margin": "#D32F2F",
                      "on-secondary-container": "#307231",
                      "on-error-container": "#93000a",
                      "secondary-container": "#acf4a4",
                      "on-tertiary-fixed-variant": "#7f2448",
                      "text-primary": "#1A1C1A",
                      "table-border": "#E0E4E0",
                      "surface-variant": "#e1e3e4",
                      "background": "#f8f9fa",
                      "surface-white": "#FFFFFF",
                      "secondary": "#2a6b2c",
                      "secondary-fixed-dim": "#91d78a",
                      "warning-margin": "#FFB300",
                      "inverse-primary": "#88d982",
                      "on-primary-fixed": "#002204",
                      "inverse-surface": "#2e3132",
                      "surface-container": "#edeeef",
                      "surface-container-highest": "#e1e3e4",
                      "surface-bright": "#f8f9fa",
                      "on-secondary": "#ffffff",
                      "primary-fixed-dim": "#88d982",
                      "on-tertiary-container": "#ffedf0",
                      "surface-tint": "#1b6d24",
                      "primary-fixed": "#a3f69c",
                      "on-primary-fixed-variant": "#005312",
                      "outline": "#707a6c",
                      "success-margin": "#4CAF50",
                      "on-secondary-fixed-variant": "#0c5216",
                      "tertiary-fixed-dim": "#ffb1c7",
                      "tertiary": "#923357",
                      "on-background": "#191c1d",
                      "error-container": "#ffdad6",
                      "on-tertiary": "#ffffff",
                      "surface-container-low": "#f3f4f5",
                      "surface-dim": "#d9dadb",
                      "on-primary": "#ffffff",
                      "on-surface-variant": "#40493d",
                      "primary-container": "#2e7d32",
                      "on-primary-container": "#cbffc2"
              },
              "borderRadius": {
                      "DEFAULT": "0.125rem",
                      "lg": "0.25rem",
                      "xl": "0.5rem",
                      "full": "0.75rem"
              },
              "spacing": {
                      "margin-desktop": "32px",
                      "gutter": "16px",
                      "base": "4px",
                      "lg": "24px",
                      "md": "16px",
                      "xl": "40px",
                      "xs": "4px",
                      "margin-mobile": "12px",
                      "sm": "8px"
              },
              "fontFamily": {
                      "body-lg": ["Inter"],
                      "body-md": ["Inter"],
                      "headline-md": ["Hanken Grotesk"],
                      "display-price": ["Hanken Grotesk"],
                      "headline-lg": ["Hanken Grotesk"],
                      "headline-lg-mobile": ["Hanken Grotesk"],
                      "numeric-mono": ["JetBrains Mono"],
                      "label-caps": ["Inter"],
                      "table-data": ["Inter"]
              },
              "fontSize": {
                      "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                      "body-md": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                      "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                      "display-price": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "headline-lg": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                      "headline-lg-mobile": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                      "numeric-mono": ["14px", {"lineHeight": "20px", "fontWeight": "500"}],
                      "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "700"}],
                      "table-data": ["14px", {"lineHeight": "20px", "fontWeight": "500"}]
              }
            },
          },
        }
    </script>
</head>
<body class="login-bg min-h-screen flex flex-col items-center justify-center p-md">
<!-- Brand Identity (The Anchor) -->
<header class="mb-xl text-center">
<div class="flex items-center justify-center gap-sm mb-base">
<span class="material-symbols-outlined text-primary text-4xl" data-icon="agriculture" style="font-variation-settings: 'FILL' 1;">agriculture</span>
</div>
<h1 class="font-headline-lg text-headline-lg text-primary tracking-tight">Toko Jadi</h1>
<p class="font-label-caps text-label-caps text-text-secondary uppercase tracking-widest">AgroFlow POS System</p>
</header>
<!-- Login Form Card -->
<main class="w-full max-w-md">
<div class="bg-surface-white border border-table-border rounded-xl p-lg shadow-sm">
<x-auth-session-status class="mb-4" :status="session('status')" />
<div class="mb-lg">
<h2 class="font-headline-md text-headline-md text-text-primary mb-xs">Selamat Datang</h2>
<p class="font-body-md text-body-md text-text-secondary">Silakan masuk untuk mengelola inventaris dan transaksi.</p>
</div>
<form class="space-y-md" method="POST" action="{{ route('login') }}">
@csrf
<!-- Username Field -->
<div class="space-y-xs">
<label class="font-label-caps text-label-caps text-on-surface-variant block" for="email">EMAIL</label>
<div class="relative flex items-center">
<span class="material-symbols-outlined absolute left-3 text-outline" data-icon="person">person</span>
<input class="w-full pl-10 pr-md py-md bg-surface border border-table-border rounded-lg font-body-md text-text-primary focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" id="email" name="email" placeholder="Email Pengguna" value="{{ old('email') }}" required="" type="email" autofocus/>
</div>
@error('email')
<span class="text-error text-xs mt-1">{{ $message }}</span>
@enderror
</div>
<!-- Password Field -->
<div class="space-y-xs">
<div class="flex justify-between items-center">
<label class="font-label-caps text-label-caps text-on-surface-variant" for="password">PASSWORD</label>
<a class="font-label-caps text-label-caps text-secondary hover:underline" href="{{ route('password.request') }}">Lupa?</a>
</div>
<div class="relative flex items-center">
<span class="material-symbols-outlined absolute left-3 text-outline" data-icon="lock">lock</span>
<input class="w-full pl-10 pr-md py-md bg-surface border border-table-border rounded-lg font-body-md text-text-primary focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" id="password" name="password" placeholder="Masukkan Kata Sandi" required="" type="password"/>
</div>
@error('password')
<span class="text-error text-xs mt-1">{{ $message }}</span>
@enderror
</div>
<!-- Remember Me (Utility Toggle) -->
<div class="flex items-center space-x-sm pt-xs">
<input class="w-5 h-5 rounded border-table-border text-primary focus:ring-primary transition-all" id="remember_me" name="remember" type="checkbox"/>
<label class="font-body-md text-body-md text-text-secondary select-none" for="remember_me">Ingat perangkat ini</label>
</div>
<!-- Submit Button -->
<button class="w-full bg-primary-container text-on-primary-container py-md rounded-lg font-headline-md text-headline-md hover:opacity-90 active:scale-95 transition-all flex items-center justify-center gap-sm mt-lg" type="submit">
<span>Masuk</span>
<span class="material-symbols-outlined text-xl" data-icon="login">login</span>
</button>
</form>
</div>
<!-- System Status Indicator (Utility Style) -->
<footer class="mt-xl text-center space-y-sm">
<div class="inline-flex items-center gap-xs px-sm py-xs bg-surface-container-high rounded-full border border-table-border">
<span class="w-2 h-2 rounded-full bg-success-margin animate-pulse"></span>
<span class="font-label-caps text-label-caps text-text-secondary">SYSTEM ONLINE</span>
</div>
<div class="block">
<p class="font-label-caps text-label-caps text-outline-variant">v2.4.0-STABLE | AGROFLOW UTILITY</p>
</div>
</footer>
</main>
<!-- Visual Background Element (Atmospheric) -->
<div class="fixed bottom-0 left-0 w-full opacity-5 pointer-events-none">
<div class="w-full h-64" data-alt="A macro photography shot of fresh morning dew on organic kale leaves. The lighting is soft, diffused sunlight typical of a morning at a high-altitude vegetable farm. The color palette is dominated by deep forest greens and bright emerald tones, maintaining a professional and earthy AgroFlow brand aesthetic. The focus is sharp on the water droplets, symbolizing clarity and precision in operations." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAEf-CfxdmK_x2zwzO0pfftxjG_Aj9xKvswCKKDkwmbJhSz6YbTUkNF8b1W3Vb3ICYHfjfdN2egj0kEQ_rkhyzGNNlWxlQ-Fe6UeB_T7kEjggalMxfO3ErPS_vwK78U3eWa_DrtVBIGAIQ2pyVSCPYbt2EdKSSj9cAOfv6te8c4r-VyMeQIkD5DcCt84mRhgpjeySspyKXAIZgvTIypjED3E7vRATNCeFCebDWPmWqn9bBwzv3CdyWDo9YkyIZLhr1abhKjvtoHsn0')"></div>
</div>
<script>
        // Micro-interaction for input focus states
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', () => {
                const icon = input.previousElementSibling;
                if (icon && icon.classList.contains('material-symbols-outlined')) {
                    icon.style.color = '#0d631b'; // primary color
                }
            });
            input.addEventListener('blur', () => {
                const icon = input.previousElementSibling;
                if (icon && icon.classList.contains('material-symbols-outlined')) {
                    icon.style.color = '#707a6c'; // outline color
                }
            });
        });
    </script>
</body></html>