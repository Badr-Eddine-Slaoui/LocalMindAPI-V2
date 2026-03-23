<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Welcome')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#fbbf24", // Electric Yellow
                        "accent-ruby": "#e11d48", // Ruby Red
                        "background-deep": "#0a0c10", // Very dark navy/grey
                        "card-dark": "#161b22", // Lighter dark shade for cards
                        "sidebar-black": "#010409", // Sleek black
                        "obsidian": "#0a0e14",
                        "deep-teal": "#042c2e",
                        "pirate-gold": "#d4af37",
                        "silver-mist": "#e2e8f0",
                        "straw-red": "#e63946",
                        "glass-dark": "rgba(10, 14, 20, 0.7)",
                        "charcoal": "#121214",
                        "grand-line-red": "#dc2626",
                        "teal-accent": "#14b8a6",
                        "purple-accent": "#7c3aed",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "1rem",
                        "xl": "1.5rem",
                        "full": "9999px"
                    },
                    boxShadow: {
                        'glow': '0 0 15px -3px rgba(251, 191, 36, 0.3)',
                    }
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        :root {
            --midnight-blue: #0A192F;
            --neon-blue: #00f2ff;
            --gold-glow: #ffcc00;
            --deep-indigo: #162447;
            --glowing-crimson: #ff003c;
            --charred-black: #0d0d0d;
            --dark-charcoal: #121212;
            --dark-navy: #0F172A;
        }
        body {
            background-color: var(--midnight-blue);
        }
        .scroll-card {
            background: linear-gradient(145deg, #111111, #050505);
            border: 1px solid rgba(0, 242, 255, 0.2);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.8), 0 0 20px rgba(0, 242, 255, 0.1);
        }
        .glow-input {
            background-color: var(--deep-indigo);
            border: 1px solid rgba(0, 242, 255, 0.3);
            transition: all 0.3s ease;
        }
        .glow-input:focus {
            border-color: var(--neon-blue);
            box-shadow: 0 0 10px rgba(0, 242, 255, 0.4);
            outline: none;
        }
        .crimson-btn {
            background-color: var(--glowing-crimson);
            box-shadow: 0 0 15px rgba(255, 0, 60, 0.5);
            transition: all 0.3s ease;
        }
        .crimson-btn:hover {
            box-shadow: 0 0 25px rgba(255, 0, 60, 0.8);
            filter: brightness(1.2);
        }
        .neon-text-blue {
            color: var(--neon-blue);
            text-shadow: 0 0 5px rgba(0, 242, 255, 0.5);
        }
        .neon-text-gold {
            color: var(--gold-glow);
            text-shadow: 0 0 5px rgba(255, 204, 0, 0.4);
        }
        .charred-edge {
            background-image: radial-gradient(circle at center, transparent 70%, rgba(0,0,0,0.8) 100%);
        }

        @layer base {
            body {
                @apply bg-background-deep text-slate-300 font-display;
            }
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .fill-icon {
            font-variation-settings: 'FILL' 1;
        }

        .question-card:hover {
            @apply border-primary/50 shadow-glow;
        }

        .night-sea-bg {
            background: radial-gradient(circle at center, #0d3b3f 0%, #0a0e14 100%);
        }
        .glass-card {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background-color: rgba(10, 14, 20, 0.75);
            border: 1px solid rgba(212, 175, 55, 0.3);
        }
        .ocean-texture {
            background-image: url(https://lh3.googleusercontent.com/aida-public/AB6AXuClo06v0jY42Txme-VqhGEjoLDgGDLtxcRr7dmw-xIRPZ4mj4cEDVXM_NQVLlJbKbSmJRgAwZOjMdH5e2fynAEc7RYa9xSDiXEkLoLjHPx3k8CuD6ugw0XcmICAjIzHmNxB8OUgHsnCFtIxGOBSi3y5wBXuMIhNZseu94itluSiPO0KUP838isxs2bnsgAO0foqzwEOqVU3ooT5B2rFupUJ5AZ5_bGpIjq9DkXaKOJLnJPyaZBUf5cdqwrFQcRPFu4Gj-sjoxr6PrA);
            background-repeat: repeat;
            mix-blend-mode: overlay;
        }
        .bounty-card {
            background-color: var(--dark-charcoal);
            border: 1px solid var(--neon-blue);
            box-shadow: 0 0 20px rgba(0, 242, 255, 0.2);
        }
        .editor-bg {
            background-color: var(--dark-navy);
            border: 1px solid rgba(0, 242, 255, 0.1);
        }
        .crimson-btn {
            background-color: var(--glowing-crimson);
            box-shadow: 0 0 15px rgba(255, 0, 60, 0.5);
            transition: all 0.3s ease;
        }
        .crimson-btn:hover {
            box-shadow: 0 0 25px rgba(255, 0, 60, 0.8);
            filter: brightness(1.2);
        }
        .neon-text-blue {
            color: var(--neon-blue);
            text-shadow: 0 0 5px rgba(0, 242, 255, 0.5);
        }
        .neon-text-gold {
            color: var(--gold-glow);
            text-shadow: 0 0 5px rgba(255, 204, 0, 0.4);
        }
        .fill-icon {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glowing-line {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.3), transparent);
            box-shadow: 0 0 8px rgba(139, 92, 246, 0.2);
        }
        .submit-glow {
            box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.2), 0 4px 15px rgba(220, 38, 38, 0.3);
        }
        .charcoal-card {
            background-color: #121214;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="min-h-screen">
    @include('partials.header')
    @yield('content')
    @include('partials.flash-message')
</body>

</html>
