<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('association.name') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha384-nRgPTkuX86pH8yjPJUAFuASXQSSl2/bBUiNV47vSYpKFxHJhbcrGnmlYpYJMeD7a" crossorigin="anonymous">
    <style>
        @font-face {
            font-family: 'Outfit';
            font-style: normal;
            font-weight: 300 700;
            font-display: swap;
            src: url('/fonts/outfit-latin.woff2') format('woff2');
        }

        :root {
            --primary-color: #7a2f1f;
            --accent-color: #F9F7D3;
            --text-dark: #333;
            --glass-bg: rgba(255, 255, 255, 0.95);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', system-ui, -apple-system, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e6d2c7ff 0%, #daccc6ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            position: relative;
            z-index: 1;
        }

        .foto {
            position: fixed;
            top: 0;
            right: 0;
            width: 50vw;
            height: 100vh;
            background-image: url('/imagens/art_back_logo.webp');
            background-size: cover;
            background-position: center;
            z-index: 10;
            transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
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
            background: rgba(122, 47, 31, 0.4);
            z-index: -1;
        }

        .foto h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 4px 8px rgba(0,0,0,0.5);
            color: white;
        }

        .foto p {
            font-size: 1.1rem;
            opacity: 0.95;
            max-width: 85%;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        .esquerda, .direita {
            width: 50vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }

        .areaLogin, .areaCadastro {
            width: 70%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding: 0;
        }

        .titulo h1 {
            color: var(--primary-color);
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .sub-titulo {
            color: #666;
            font-size: 1rem;
            margin-bottom: 25px;
        }

        .inputarea {
            margin-bottom: 15px;
            width: 100%;
        }

        .inputarea label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .inputarea input {
            width: 100%;
            padding: 16px;
            border-radius: 15px;
            border: none;
            background: #ebe6e6;
            color: var(--text-dark);
            font-size: 1rem;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .inputarea input:focus {
            outline: solid 2px var(--primary-color);
            background: #fff;
        }

        .btosLines button {
            width: 100%;
            padding: 16px;
            border-radius: 15px;
            border: none;
            background: var(--primary-color);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
            box-shadow: 0 5px 15px rgba(122, 47, 31, 0.2);
            margin-top: 10px;
        }

        .btosLines button:hover {
            background: #5c2417;
            transform: scale(0.97);
        }

        .toggle-link {
            text-align: center;
            margin-top: 20px;
            color: var(--primary-color);
            font-weight: 700;
            cursor: pointer;
            font-size: 0.95rem;
            text-decoration: underline;
        }

        .alert-danger {
            background: #ffecec;
            color: #e74c3c;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            border-left: 4px solid #e74c3c;
            width: 100%;
        }

        @media (max-width: 767px) {
            body {
                background: linear-gradient(135deg, #7a2f1f 0%, #4a1d13 100%);
                padding: 0;
                overflow-y: auto;
            }
            .container {
                height: 100dvh;
                flex-direction: column;
                background: transparent;
                width: 100%;
                overflow-y: auto;
            }
            .foto { display: none; }
            .esquerda, .direita {
                width: 100%;
                height: 100dvh;
                justify-content: center;
            }

            .areaLogin, .areaCadastro {
                background: var(--accent-color);
                border-radius: 20px;
                padding: 20px 18px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.3);
                width: 100%;
                max-width: 100%;
                gap: 0.6rem;
            }

            .areaLogin .inputarea, .areaCadastro .inputarea {
                margin-bottom: 6px;
            }

            .areaLogin .inputarea input, .areaCadastro .inputarea input {
                padding: 10px 14px;
                font-size: 0.9rem;
            }

            .areaLogin .btosLines button, .areaCadastro .btosLines button {
                padding: 12px;
                font-size: 0.9rem;
            }

            .areaLogin .titulo h1, .areaCadastro .titulo h1 {
                font-size: 1.4rem;
                margin-bottom: 0;
            }

            .areaLogin .sub-titulo, .areaCadastro .sub-titulo {
                font-size: 0.85rem;
                margin-bottom: 10px;
            }

            .areaLogin .alert-danger, .areaCadastro .alert-danger {
                padding: 8px;
                font-size: 0.8rem;
                margin-bottom: 8px;
            }

            .areaLogin .toggle-link, .areaCadastro .toggle-link {
                margin-top: 10px;
                font-size: 0.85rem;
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

        <div class="foto" id="caixaFoto">
            <h2 id="sideTitle">Bem-vindo!</h2>
            <p id="sideDesc">Acesse sua conta na {{ config('association.name_short') }}.</p>
        </div>

        <div class="esquerda">
            <div class="areaLogin">
                <div class="titulo"><h1>Entrar</h1></div>
                <p class="sub-titulo">Entre com sua conta.</p>

                @if ($errors->any())
                    <div class="alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" style="width: 100%;">
                    @csrf
                    <div class="inputarea">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="nome@exemplo.com" required autofocus>
                    </div>
                    <div class="inputarea">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                    <div class="btosLines">
                        <button type="submit">Entrar no Painel</button>
                    </div>
                </form>

                <p class="toggle-link" id="LoginBtn">Criar nova conta</p>
            </div>
        </div>

        <div class="direita">
            <div class="areaCadastro">
                <div class="titulo"><h1>Criar Conta</h1></div>
                <p class="sub-titulo">Cadastre-se para comprar ou se tornar artesão.</p>

                <form method="POST" action="{{ route('register') }}" style="width: 100%;">
                    @csrf
                    <div class="inputarea">
                        <label for="name">Nome Completo</label>
                        <input type="text" id="name" name="name" placeholder="Seu nome" value="{{ old('name') }}" required>
                    </div>
                    <div class="inputarea">
                        <label for="reg_email">E-mail</label>
                        <input type="email" id="reg_email" name="email" placeholder="nome@exemplo.com" required>
                    </div>
                    <div class="inputarea">
                        <label for="reg_password">Senha</label>
                        <input type="password" id="reg_password" name="password" placeholder="Mínimo 8 caracteres" required>
                    </div>
                    <div class="inputarea">
                        <label for="password_confirmation">Confirmar Senha</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repita a senha" required>
                    </div>
                    <div class="btosLines">
                        <button type="submit">Enviar Solicitação</button>
                    </div>
                </form>

                <p class="toggle-link" id="RegistrarBtn">Já possui conta? Entrar</p>
            </div>
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
                caixaFoto.style.right = "50vw";
                setTimeout(() => {
                    caixaFoto.style.backgroundImage = "url(/imagens/art_back_logo.webp)";
                    sideTitle.innerText = "Novo por aqui?";
                    sideDesc.innerText = "Crie sua conta na {{ config('association.name_short') }}.";
                }, 400);
            }
        }

        function showLogin() {
            if (isMobile()) {
                container.classList.remove("register-active");
            } else {
                caixaFoto.style.right = "0";
                setTimeout(() => {
                    caixaFoto.style.backgroundImage = "url(/imagens/art_back_logo.webp)";
                    sideTitle.innerText = "Bem-vindo!";
                    sideDesc.innerText = "Acesse sua conta na {{ config('association.name_short') }}.";
                }, 400);
            }
        }

        irRegister.addEventListener("click", showRegister);
        irLogin.addEventListener("click", showLogin);

        window.addEventListener("resize", () => {
            if (!isMobile()) {
                container.classList.remove("register-active");
                caixaFoto.style.right = container.classList.contains("register-active") ? "50vw" : "0";
            }
        });
    </script>
</body>
</html>
