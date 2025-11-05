<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name') }}</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
        }

        /* Animated Gradient Background */
        .gradient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg,
                #1e3a8a 0%,
                #3730a3 25%,
                #7e22ce 50%,
                #be185d 75%,
                #dc2626 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            z-index: 0;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Geometric Shapes */
        .geometric-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 20s infinite;
        }

        .shape1 {
            width: 300px;
            height: 300px;
            border: 2px solid white;
            border-radius: 50%;
            top: 10%;
            left: 10%;
            animation-duration: 25s;
        }

        .shape2 {
            width: 200px;
            height: 200px;
            border: 2px solid white;
            transform: rotate(45deg);
            top: 60%;
            right: 15%;
            animation-duration: 30s;
        }

        .shape3 {
            width: 150px;
            height: 150px;
            border: 2px solid white;
            border-radius: 30%;
            bottom: 20%;
            left: 20%;
            animation-duration: 20s;
        }

        .shape4 {
            width: 100px;
            height: 100px;
            border: 2px solid white;
            border-radius: 50%;
            top: 30%;
            right: 30%;
            animation-duration: 35s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(30px, -30px) rotate(90deg); }
            50% { transform: translate(-20px, 20px) rotate(180deg); }
            75% { transform: translate(20px, 30px) rotate(270deg); }
        }

        /* Particles Canvas */
        #particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Login Container */
        .login-wrapper {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            overflow: hidden;
            max-width: 1100px;
            width: 100%;
            position: relative;
        }

        /* Glassmorphism effect with animation */
        .login-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .login-left {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .login-right {
            padding: 3rem;
            background: rgba(255, 255, 255, 0.95);
            position: relative;
        }

        /* AI Logo Animation */
        .login-logo {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            position: relative;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }

        .ai-ring {
            position: absolute;
            width: 120px;
            height: 120px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: rotate 4s linear infinite;
        }

        .ai-ring:nth-child(2) {
            width: 140px;
            height: 140px;
            animation-duration: 6s;
            animation-direction: reverse;
        }

        @keyframes rotate {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Form Styling */
        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: white;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
        }

        .input-group-text {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-right: none;
            color: #6366f1;
            border-radius: 0.75rem 0 0 0.75rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
            background-size: 200% 200%;
            border: none;
            padding: 0.875rem 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 0.75rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            background-position: 100% 0;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }

        .feature-list {
            list-style: none;
            padding: 0;
            text-align: left;
        }

        .feature-list li {
            padding: 0.75rem 0;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            opacity: 0;
            animation: fadeInUp 0.6s forwards;
        }

        .feature-list li:nth-child(1) { animation-delay: 0.2s; }
        .feature-list li:nth-child(2) { animation-delay: 0.4s; }
        .feature-list li:nth-child(3) { animation-delay: 0.6s; }
        .feature-list li:nth-child(4) { animation-delay: 0.8s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-list i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Alert Styling */
        .alert {
            border-radius: 0.75rem;
            border: none;
            backdrop-filter: blur(10px);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .login-left {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
        }

        /* Loading animation for logo */
        .logo-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
        }

        .logo-icon {
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>
    <!-- Animated Gradient Background -->
    <div class="gradient-bg"></div>

    <!-- Geometric Shapes -->
    <div class="geometric-shapes">
        <div class="shape shape1"></div>
        <div class="shape shape2"></div>
        <div class="shape shape3"></div>
        <div class="shape shape4"></div>
    </div>

    <!-- Particles Canvas -->
    <canvas id="particles-canvas"></canvas>

    <!-- Login Content -->
    <div class="login-wrapper">
        <div class="login-container">
            <div class="row g-0">
                <div class="col-lg-5 login-left">
                    <div class="text-center">
                        <div class="logo-container">
                            <div class="ai-ring"></div>
                            <div class="ai-ring"></div>
                            <div class="login-logo logo-icon">
                                <i class="fas fa-ship"></i>
                            </div>
                        </div>
                        <h3 class="mb-3 fw-bold">Quick International Shipping Company</h3>
                        <p class="mb-4 opacity-75">AI-Powered freight forwarding operations</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Multi-branch Management</li>
                            <li><i class="fas fa-check-circle"></i> Real-time Shipment Tracking</li>
                            <li><i class="fas fa-check-circle"></i> Financial Management</li>
                            <li><i class="fas fa-check-circle"></i> Customer Portal</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-7 login-right">
                    <div class="mb-5">
                        <h2 class="fw-bold mb-2">Welcome Back!</h2>
                        <p class="text-muted">Please login to your account</p>
                    </div>

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login.submit') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="admin@logistics.com" value="{{ old('email', 'admin@logistics.com') }}" required>
                            </div>
                            @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Enter your password" value="admin123" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-radius: 0 0.75rem 0.75rem 0;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>

                        <div class="text-center">
                            <a href="#" class="text-decoration-none" style="color: #6366f1;">Forgot Password?</a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center text-muted small">
                        <p class="mb-2">Demo Credentials:</p>
                        <p>Email: admin@logistics.com | Password: admin123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Particles Animation
        const canvas = document.getElementById('particles-canvas');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let particlesArray = [];
        const numberOfParticles = 100;

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 3 + 1;
                this.speedX = Math.random() * 2 - 1;
                this.speedY = Math.random() * 2 - 1;
                this.opacity = Math.random() * 0.5 + 0.2;
            }

            update() {
                this.x += this.speedX;
                this.y += this.speedY;

                if (this.x > canvas.width || this.x < 0) {
                    this.speedX = -this.speedX;
                }
                if (this.y > canvas.height || this.y < 0) {
                    this.speedY = -this.speedY;
                }
            }

            draw() {
                ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.closePath();
                ctx.fill();
            }
        }

        function init() {
            particlesArray = [];
            for (let i = 0; i < numberOfParticles; i++) {
                particlesArray.push(new Particle());
            }
        }

        function connectParticles() {
            for (let a = 0; a < particlesArray.length; a++) {
                for (let b = a; b < particlesArray.length; b++) {
                    let distance = Math.sqrt(
                        Math.pow(particlesArray[a].x - particlesArray[b].x, 2) +
                        Math.pow(particlesArray[a].y - particlesArray[b].y, 2)
                    );
                    if (distance < 120) {
                        ctx.strokeStyle = `rgba(255, 255, 255, ${0.2 - distance / 600})`;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                        ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
                        ctx.stroke();
                    }
                }
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (let i = 0; i < particlesArray.length; i++) {
                particlesArray[i].update();
                particlesArray[i].draw();
            }

            connectParticles();
            requestAnimationFrame(animate);
        }

        init();
        animate();

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            init();
        });

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Form validation
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
