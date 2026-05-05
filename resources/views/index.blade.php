<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Associação dos Artesãos</title>
    <link rel="stylesheet" href="{{ url('css/style-index.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* ==========================================
           ESTILOS PARA LOGIN SOCIAL
           ========================================== */
        .social-login-container {
            margin-top: 20px;
            text-align: center;
        }
        .social-login-title {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
            position: relative;
        }
        .social-login-title::before, .social-login-title::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #ccc;
        }
        .social-login-title::before { left: 0; }
        .social-login-title::after { right: 0; }
        
        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .btn-social {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            transition: transform 0.2s, opacity 0.2s;
            text-decoration: none;
        }
        .btn-social:hover {
            transform: scale(1.1);
            opacity: 0.9;
        }
        .btn-google { background-color: #db4437; }
        .btn-apple { background-color: #000000; }
        .btn-microsoft { background-color: #00a4ef; }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            border: 1px solid #f5c6cb;
        }

        /* ==========================================
           RESPONSIVIDADE DO LOGIN - MOBILE
           ========================================== */
        
        @media (max-width: 767px) {
            .container {
                flex-direction: column !important;
                height: auto !important;
                background: linear-gradient(135deg, #e6d2c7ff 0%, #daccc6ff 100%) !important;
                min-height: 100vh;
                padding: 20px;
            }
            .esquerda, .direita {
                width: 100% !important;
                max-width: none !important;
                padding: 20px !important;
                background: transparent !important;
            }
            .foto {
                display: none !important;
            }
            body {
                background: linear-gradient(135deg, #8B5A42 0%, #5C3A2C 100%) !important;
            }
            .direita {
                display: none !important;
            }
            .direita.show-register {
                display: block !important;
            }
            .container.register-active .esquerda {
                display: none !important;
            }
            .areaLogin, .areaCadastro {
                background: rgba(255, 255, 255, 0.95) !important;
                padding: 30px 20px !important;
                border-radius: 15px !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
            }
            .inputarea input {
                font-size: 16px !important;
                padding: 12px !important;
            }
            .btosLines button {
                font-size: 16px !important;
                padding: 14px !important;
                width: 100% !important;
            }
        }
        
        @media (min-width: 768px) {
            .foto { display: block !important; }
            .container.register-active .esquerda,
            .direita.show-register { display: flex !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="foto"></div> 

        <!-- ================= LOGIN ================= -->
        <div class="esquerda">
            <div class="areaLogin">
                <div class="titulo"><h1>Acesso Administrativo</h1></div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="inputarea">
                        <label for="email">EMAIL</label>
                        <input type="email" name="email" placeholder="Exemplo@gmail.com" required autofocus>
                    </div>
                    <div class="inputarea">
                        <label for="password">SENHA</label>
                        <input type="password" name="password" placeholder="Digite sua senha" required>
                    </div>
                    <div class="btosLines">
                        <button type="submit" id="btnLogin">LOGIN</button>
                    </div>
                </form>

                <div class="social-login-container">
                    <p class="social-login-title">Ou entre com</p>
                    <div class="social-buttons">
                        <a href="{{ route('social.login', 'google') }}" class="btn-social btn-google" title="Google">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="{{ route('social.login', 'apple') }}" class="btn-social btn-apple" title="Apple">
                            <i class="fab fa-apple"></i>
                        </a>
                        <a href="{{ route('social.login', 'microsoft') }}" class="btn-social btn-microsoft" title="Microsoft">
                            <i class="fab fa-microsoft"></i>
                        </a>
                    </div>
                </div>

                <div class="btosLines">
                    <p id="LoginBtn">Solicitar acesso</p>
                </div>
            </div>
        </div>

        <!-- ================= CADASTRO (Oculto para Admin-only se desejar, mas mantendo a estrutura) ================= -->
        <div class="direita">
            <div class="areaCadastro">
                <div class="titulo"><h1>Solicitar Registro</h1></div>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="inputarea">
                        <label for="name">NOME</label>
                        <input type="text" name="name" placeholder="Seu nome" value="{{ old('name') }}" required>
                    </div>
                    <div class="inputarea">
                        <label for="email">EMAIL</label>
                        <input type="email" name="email" placeholder="Exemplo@gmail.com" required>
                    </div>
                    <div class="inputarea">
                        <label for="password">SENHA</label>
                        <input type="password" name="password" placeholder="Crie sua senha" required>
                    </div>
                    <div class="inputarea">
                        <label for="password_confirmation">CONFIRMAR SENHA</label>
                        <input type="password" name="password_confirmation" placeholder="Repita sua senha" required>
                    </div>
                    <div class="btosLines">
                        <button type="submit">SOLICITAR</button>
                    </div>
                    <div class="btosLines">
                        <p id="RegistrarBtn">Já tenho uma conta</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let caixaFoto = document.querySelector(".foto");
        let irLogin = document.getElementById("RegistrarBtn");
        let irRegister = document.getElementById("LoginBtn");
        let container = document.querySelector(".container");
        let direita = document.querySelector(".direita");

        function isMobile() { return window.innerWidth <= 767; }

        function showRegister() {
            if (isMobile()) {
                container.classList.add("register-active");
                direita.classList.add("show-register");
            } else {
                if (caixaFoto) {
                    caixaFoto.style.right = "50vw";
                    setTimeout(() => {
                        caixaFoto.style.backgroundImage = "url(/imagens/banquin.png)";
                    }, 200);
                }
            }
        }

        function showLogin() {
            if (isMobile()) {
                container.classList.remove("register-active");
                direita.classList.remove("show-register");
            } else {
                if (caixaFoto) {
                    caixaFoto.style.right = "0";
                    setTimeout(() => {
                        caixaFoto.style.backgroundImage = "url(/imagens/fundo.png)";
                    }, 200);
                }
            }
        }

        if (irRegister) irRegister.addEventListener("click", showRegister);
        if (irLogin) irLogin.addEventListener("click", showLogin);

        window.addEventListener("resize", () => {
            if (!isMobile()) {
                container.classList.remove("register-active");
                direita.classList.remove("show-register");
            }
        });
    </script>
</body>
</html>
