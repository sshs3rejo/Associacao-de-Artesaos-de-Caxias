# Associação dos Artesãos de Caxias

> Plataforma web que reúne e exibe os produtos, eventos e a história da associação. Construída com **Laravel 12** e **Bootstrap 5**, focada em design premium, navegação fluida e total responsividade.

## 📖 Sumário  

- [Sobre o Projeto](#sobre-o-projeto)  
- [Principais Funcionalidades](#principais-funcionalidades)  
- [Tecnologias Utilizadas](#tecnologias-utilizadas)  
- [Instalação](#instalação)  
- [Configuração do Ambiente](#configuração-do-ambiente)  
- [Autenticação Social](#autenticação-social)
- [Como Executar a Aplicação](#como-executar-a-aplicação)  
- [Deploy](#deploy)  
- [Licença](#licença)  

---

## Sobre o Projeto  

A **Associação dos Artesãos de Caxias** reúne artesãos da região de Caxias (MA) e deseja divulgar seus produtos, eventos e seu legado cultural. Este portal web oferece:

- **Design unificado** – navbar, footer e cards seguem a paleta de cores da identidade visual (marrom terracota `#7a2f1f`, creme `#F9F7D3`).
- **Acesso Administrativo Seguro** – Login restrito a administradores para gestão de conteúdo.
- **Integração Social** – Autenticação rápida via Google, Apple e Microsoft.
- **Responsividade Premium** – Experiência otimizada para desktop e mobile com layout adaptativo.
- **Contato Imediato** – Botão flutuante de WhatsApp e formulário de contato integrado.

---

## Principais Funcionalidades  

| ✅ | Funcionalidade | Detalhes |
|---|----------------|----------|
| **Login Administrativo** | Sistema de login seguro com restrição de acesso apenas para usuários com role `admin`. |
| **Login Social** | Integração com **Google, Apple e Microsoft** via Laravel Socialite. |
| **Histórico de Vendas** | Painel administrativo para visualização e acompanhamento de todas as vendas realizadas. |
| **Botão WhatsApp** | Botão flutuante presente em todas as páginas para contato direto e imediato. |
| **Premium Login UI** | Interface de login moderna com efeitos de glassmorphism e animações deslizantes (Desktop). |
| **Navbar Responsiva** | Menu colapsável otimizado para mobile com dropdown administrativo personalizado. |
| **Transição SPA‑like** | Conteúdo principal com fade‑in/out suave, mantendo a navegação estática. |
| **Grid de Produtos** | Layout adaptativo que escala até 6 colunas em telas ultra‑wide. |

---

## Tecnologias Utilizadas  

| Camada | Tecnologia | Versão |
|--------|------------|--------|
| **Back‑end** | PHP | 8.2+ |
| | Laravel | 12.x |
| | Laravel Socialite | ^5.16 |
| **Front‑end** | Bootstrap | 5.3 |
| | Font Awesome | 6.5 |
| | Outfit Font | Google Fonts |
| **Banco de Dados** | MySQL / MariaDB | 10.6+ |

---

## Instalação  

```bash
# 1️⃣ Clone o repositório
git clone https://github.com/sshs3rejo/Associacao-de-Artesaos-de-Caxias.git
cd Associacao-de-Artesaos-de-Caxias

# 2️⃣ Instale as dependências
composer install
npm install

# 3️⃣ Configure o ambiente
cp .env.example .env
php artisan key:generate

# 4️⃣ Execute as migrações
php artisan migrate --seed

# 5️⃣ Compile os assets
npm run build
```

---

## Autenticação Social

Para habilitar o login social, adicione as seguintes chaves ao seu `.env`:

```env
# Google
GOOGLE_CLIENT_ID=seu_id
GOOGLE_CLIENT_SECRET=sua_secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# Apple
APPLE_CLIENT_ID=seu_id
APPLE_CLIENT_SECRET=sua_secret
APPLE_REDIRECT_URI="${APP_URL}/auth/apple/callback"

# Microsoft
MICROSOFT_CLIENT_ID=seu_id
MICROSOFT_CLIENT_SECRET=sua_secret
MICROSOFT_REDIRECT_URI="${APP_URL}/auth/microsoft/callback"
```

---

## Como Executar a Aplicação  

```bash
# Servidor de desenvolvimento
php artisan serve

# Acesse no navegador:
http://localhost:8000
```

### Credenciais de Admin (Seeders)
- **Email:** `admin@artesao.com`
- **Senha:** `password`

---

## Deploy  

Para instruções detalhadas de deploy, consulte o arquivo [deploy_guide.md](deploy_guide.md).

---

## Licença  

Distribuído sob a licença **MIT**. Consulte o arquivo `LICENSE` para mais detalhes.
