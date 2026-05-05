<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - Associação dos Artesãos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #7a2f1f;
            --accent-color: #F9F7D3;
            --text-dark: #333;
            --glass-bg: rgba(249, 247, 211, 0.98);
            --glass-border: rgba(122, 47, 31, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #7a2f1f 0%, #4a1d13 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            height: 600px;
            display: flex;
            background: var(--glass-bg);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
            position: relative;
            z-index: 1;
        }

        /* FOTO DESLIZANTE (DESKTOP) */
        .foto {
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background-image: url('/imagens/fundo.png');
            background-size: cover;
            background-position: center;
            z-index: 10;
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: var(--accent-color);
            padding: 40px;
            text-align: center;
        }

        .foto::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(122, 47, 31, 0.3);
            z-index: -1;
        }

        .foto h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }

        .foto p {
            font-size: 1rem;
            opacity: 0.95;
            max-width: 85%;
        }

        /* PAINÉIS */
        .esquerda, .direita {
            width: 50%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            z-index: 5;
        }

        .titulo h1 {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .sub-titulo {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        .inputarea {
            margin-bottom: 15px;
        }

        .inputarea label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .inputarea input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 2px solid #eee;
            background: #fff;
            color: var(--text-dark);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .inputarea input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: #fff;
        }

        .btosLines button {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: none;
            background: var(--primary-color);
            color: var(--accent-color);
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(122, 47, 31, 0.2);
        }

        .btosLines button:hover {
            background: #5c2417;
            transform: translateY(-2px);
        }

        .social-login-container {
            margin-top: 25px;
        }

        .social-login-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #aaa;
            font-size: 0.8rem;
            margin-bottom: 15px;
        }

        .social-login-title::before, .social-login-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .btn-social {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-social:hover {
            transform: translateY(-3px);
            opacity: 0.9;
        }

        .btn-google { background: #fff; color: #db4437; border: 1px solid #eee; }
        .btn-apple { background: #000; }
        .btn-microsoft { background: #00a4ef; }

        .toggle-link {
            text-align: center;
            margin-top: 20px;
            color: var(--primary-color);
            font-weight: 700;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: #ffecec;
            color: #e74c3c;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.85rem;
            border-left: 3px solid #e74c3c;
        }

        /* RESPONSIVIDADE MOBILE */
        @media (max-width: 767px) {
            body { background: var(--primary-color); padding: 20px; }
            .container { height: auto; flex-direction: column; background: transparent; box-shadow: none; }
            .foto { display: none; }
            .esquerda, .direita {
                width: 100%;
                background: var(--accent-color);
                border-radius: 20px;
                padding: 35px 25px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            }
            .direita { display: none; }
            .container.register-active .esquerda { display: none; }
            .container.register-active .direita { display: flex; }
            .titulo h1 { font-size: 1.8rem; text-align: center; }
            .sub-titulo { text-align: center; }
        }
    </style>
</head>
<body>
    <div class="container" id="mainContainer">
        
        <!-- FOTO DESLIZANTE -->
        <div class="foto" id="caixaFoto">
            <h2 id="sideTitle">Bem-vindo!</h2>
            <p id="sideDesc">Acesse o painel administrativo da Associação.</p>
        </div>

        <!-- LOGIN -->
        <div class="esquerda">
            <div class="titulo"><h1>Acesso Admin</h1></div>
            <p class="sub-titulo">Entre com sua conta administrativa.</p>

            @if ($errors->any())
                <div class="alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="inputarea">
                    <label>E-mail</label>
                    <input type="email" name="email" placeholder="nome@exemplo.com" required autofocus>
                </div>
                <div class="inputarea">
                    <label>Senha</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <div class="btosLines">
                    <button type="submit">Entrar no Painel</button>
                </div>
            </form>

            <div class="social-login-container">
                <p class="social-login-title">Ou continue com</p>
                <div class="social-buttons">
                    <a href="{{ route('social.login', 'google') }}" class="btn-social btn-google"><i class="fab fa-google"></i></a>
                    <a href="{{ route('social.login', 'apple') }}" class="btn-social btn-apple"><i class="fab fa-apple"></i></a>
                    <a href="{{ route('social.login', 'microsoft') }}" class="btn-social btn-microsoft"><i class="fab fa-microsoft"></i></a>
                </div>
            </div>

            <p class="toggle-link" id="LoginBtn">Solicitar novo acesso</p>
        </div>

        <!-- CADASTRO -->
        <div class="direita">
            <div class="titulo"><h1>Solicitar Acesso</h1></div>
            <p class="sub-titulo">Cadastre seus dados para avaliação.</p>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="inputarea">
                    <label>Nome Completo</label>
                    <input type="text" name="name" placeholder="Seu nome" value="{{ old('name') }}" required>
                </div>
                <div class="inputarea">
                    <label>E-mail</label>
                    <input type="email" name="email" placeholder="nome@exemplo.com" required>
                </div>
                <div class="inputarea">
                    <label>Senha</label>
                    <input type="password" name="password" placeholder="Mínimo 8 caracteres" required>
                </div>
                <div class="inputarea">
                    <label>Confirmar Senha</label>
                    <input type="password" name="password_confirmation" placeholder="Repita a senha" required>
                </div>
                <div class="btosLines">
                    <button type="submit">Enviar Solicitação</button>
                </div>
            </form>
            
            <p class="toggle-link" id="RegistrarBtn">Já possui conta? Entrar</p>
        </div>

    </div>

    <script>
        const caixaFoto = document.getElementById('caixaFoto');
        const irLogin = document.getElementById("RegistrarBtn");
        const irRegister = document.getElementById("LoginBtn");
        const container = document.getElementById("mainContainer");
        const sideTitle = document.getElementById('sideTitle');
        const sideDesc = document.getElementById('sideDesc');

        function isMobile() { return window.innerWidth <= 767; }

        function showRegister() {
            if (isMobile()) {
                container.classList.add("register-active");
            } else {
                caixaFoto.style.right = "50%";
                setTimeout(() => {
                    caixaFoto.style.backgroundImage = "url(/imagens/banquin.png)";
                    sideTitle.innerText = "Novo por aqui?";
                    sideDesc.innerText = "Solicite seu acesso administrativo.";
                }, 200);
            }
        }

        function showLogin() {
            if (isMobile()) {
                container.classList.remove("register-active");
            } else {
                caixaFoto.style.right = "0";
                setTimeout(() => {
                    caixaFoto.style.backgroundImage = "url(/imagens/fundo.png)";
                    sideTitle.innerText = "Bem-vindo!";
                    sideDesc.innerText = "Acesse o painel administrativo da Associação.";
                }, 200);
            }
        }

        irRegister.addEventListener("click", showRegister);
        irLogin.addEventListener("click", showLogin);

        window.addEventListener("resize", () => {
            if (!isMobile()) {
                container.classList.remove("register-active");
                caixaFoto.style.right = container.classList.contains("register-active") ? "50%" : "0";
            }
        });
    </script>
</body>
</html>
