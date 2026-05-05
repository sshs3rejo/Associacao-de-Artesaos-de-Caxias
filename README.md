# Associação dos Artesãos de Caxias

> Plataforma web que reúne e exibe os produtos, eventos e a história da associação. Construída com **Laravel 12** e **Bootstrap 5**, focada em design premium, navegação fluida e total responsividade.


## 📖 Sumário  

- [Sobre o Projeto](#sobre-o-projeto)  
- [Principais Funcionalidades](#principais-funcionalidades)  
- [Demonstração & Screenshots](#demonstração--screenshots)  
- [Tecnologias Utilizadas](#tecnologias-utilizadas)  
- [Instalação](#instalação)  
- [Configuração do Ambiente](#configuração-do-ambiente)  
- [Como Executar a Aplicação](#como-executar-a-aplicação)  
- [Testes](#testes)  
- [Deploy](#deploy)  
- [Contribuindo](#contribuindo)  
- [Roadmap](#roadmap)  
- [Licença](#licença)  
- [Agradecimentos](#agradecimentos)  

---

## Sobre o Projeto  

A **Associação dos Artesãos de Caxias** reúne artesãos da região de Caxias (MA) e deseja divulgar seus produtos, eventos e seu legado cultural. Este portal web oferece:

- **Design unificado** – navbar, footer e cards seguem a paleta de cores da identidade visual (marrom terracota `#7a2f1f`, creme `#F9F7D3`, destaque `#c85a3a`).
- **Navegação elegante** – transição de página tipo SPA (fade‑in/out) e menu “Categorias” como popup ao lado da barra de busca.
- **Responsividade total** – funciona perfeitamente de smartphones a monitores ultra‑wide, com grid que escala de 1 a 6 colunas.
- **Código limpo e modular** – Blade components reutilizáveis (navbar, footer, cards), rotas RESTful, Eloquent models bem estruturados.

---

## Principais Funcionalidades  

| ✅ | Funcionalidade | Detalhes |
|---|----------------|----------|
| **Navbar Bootstrap 5** | Barra de navegação fixa, colapsável em mobile, com logo e nome completo da associação. |
| **Popup de Categorias** | Botão “Categorias” dentro da barra de busca abre um dropdown sem ocupar espaço extra. |
| **Transição de página SPA‑like** | Conteúdo principal faz fade‑in/out (150 ms) via CSS/JS, mantendo navbar/footer estáticos. |
| **Grid de Produtos ultra‑wide** | Até 6 colunas em telas grandes, cards com hover lift, badge “Esgotado”, botões de ação. |
| **Página de Eventos** | Lista de eventos com cards responsivos, filtro por categoria via dropdown. |
| **Footer consistente** | Texto institucional, links de navegação, ícones sociais e copyright dinâmico. |
| **Favicon** | Logo exibida na aba do navegador. |
| **Testes automatizados** | Tests de unidade para controllers, models e rotas (`php artisan test`). |
| **Docker opcional** | `docker-compose.yml` para ambiente de desenvolvimento isolado. |
| **Acessibilidade** | Uso de contrastes adequados, atributos `aria-` nos botões e foco visível. |

---

## Demonstração & Screenshots  

*(Coloque capturas de tela ou GIFs na pasta `docs/screenshots/` e referencie-as aqui.)*  

```markdown
![Navbar – Desktop](./docs/screenshots/navbar-desktop.png)
![Navbar – Mobile](./docs/screenshots/navbar-mobile.png)
![Popup de Categorias](./docs/screenshots/popup-categorias.gif)
![Página de Produtos – Grid Ultra‑wide](./docs/screenshots/produtos-grid.png)
![Página de Eventos – Card Hover](./docs/screenshots/eventos-hover.gif)
```

> **Dica:** Use um serviço como `https://squoosh.app/` para otimizar imagens antes de commitar.

---

## Tecnologias Utilizadas  

| Camada | Tecnologia | Versão |
|--------|------------|--------|
| **Back‑end** | PHP | 8.5 |
| | Laravel | 12.37 |
| | Composer | ≥2.2 |
| **Front‑end** | Bootstrap | 5.3 |
| | Font Awesome | 6.5 |
| | Bootstrap Icons | 1.11 |
| | CSS Custom (variáveis) | — |
| **Banco de Dados** | MySQL / MariaDB | 10.6+ |
| **DevOps** | Git | — |
| | Docker (opcional) | 24.0+ |
| **CI** | GitHub Actions (testes) | — |

---

## Instalação  

```bash
# 1️⃣ Clone o repositório
git clone https://github.com/sshs3rejo/Associacao-de-Artesaos-de-Caxias.git
cd Associacao-de-Artesaos-de-Caxias

# 2️⃣ Instale as dependências PHP
composer install

# 3️⃣ Copie o .env de exemplo e configure
cp .env.example .env
# ► Ajuste DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL, etc.

# 4️⃣ Gere a chave da aplicação
php artisan key:generate

# 5️⃣ Crie o banco de dados e execute as migrações + seeders
php artisan migrate --seed

# 6️⃣ Instale dependências front‑end (Node, NPM)
npm install
npm run build   # ou `npm run dev` para desenvolvimento
```

> **Obs.:** Se preferir usar Docker, veja a seção *Deploy* abaixo.

---

## Configuração do Ambiente  

| Variável | Exemplo | Comentário |
|----------|---------|------------|
| `APP_NAME` | `Associação dos Artesãos de Caxias` | Nome exibido em emails, título da página etc. |
| `APP_URL` | `http://127.0.0.1:8000` | URL base da aplicação. |
| `DB_CONNECTION` | `mysql` | Driver do BD. |
| `DB_HOST` | `127.0.0.1` | Host do MySQL. |
| `DB_PORT` | `3306` | Porta padrão. |
| `DB_DATABASE` | `artesao_caxias` | Nome do banco. |
| `DB_USERNAME` | `root` | Usuário do BD. |
| `DB_PASSWORD` | `secret` | Senha do BD. |
| `MAIL_MAILER` | `log` | Para desenvolvimento, envia e‑mails para o log. |
| `CACHE_DRIVER` | `file` | Cache local (pode mudar para `redis`). |

Depois de definir, rode:

```bash
php artisan config:cache
php artisan route:cache
```

---

## Como Executar a Aplicação  

```bash
# Servidor de desenvolvimento (Laravel built‑in)
php artisan serve

# Ou, com Laravel Sail (Docker)
./vendor/bin/sail up -d

# Acesse no navegador:
http://localhost:8000
```

### Credenciais de Teste (seeders)

- **Admin:** `admin@artesao.com` | senha: `password`
- **Usuário padrão:** `user@artesao.com` | senha: `password`

*(Altere ou remova antes de colocar em produção.)*

---

## Testes  

```bash
# Executa a suite de testes (PHPUnit)
php artisan test

# Testes de navegador (Laravel Dusk) – opcional
php artisan dusk
```

> **Cobertura mínima recomendada:** 80 % de cobertura de código. O GitHub Action do CI verifica isso a cada push.

---

## Deploy  

### Opção 1 – **Servidor VPS (Apache/Nginx)**  

1. **Crie um host virtual** apontando para o diretório `public/`.  
2. **Instale as extensões PHP necessárias** (`pdo_mysql`, `mbstring`, `openssl`, `zip`, `exif`).  
3. **Execute**:

```bash
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

4. **Configure o cron** para jobs de fila e limpeza:

```bash
* * * * * php /caminho/para/artisan schedule:run >> /dev/null 2>&1
```

### Opção 2 – **Docker Compose** (produção rápida)

```yaml
# docker-compose.yml (exemplo resumido)
version: "3.8"

services:
  app:
    image: php:8.5-fpm
    container_name: artesao_app
    working_dir: /var/www/html
    volumes:
      - ./:/var/www/html
    environment:
      - APP_ENV=production
      - APP_KEY=${APP_KEY}
      - DB_HOST=db
    depends_on:
      - db

  web:
    image: nginx:stable-alpine
    container_name: artesao_web
    ports:
      - "80:80"
    volumes:
      - ./nginx/conf:/etc/nginx/conf.d
      - ./:/var/www/html
    depends_on:
      - app

  db:
    image: mariadb:10.6
    container_name: artesao_db
    environment:
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_DATABASE: artesao_caxias
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

```bash
docker compose up -d
docker exec -it artesao_app bash
composer install --no-dev
php artisan migrate --force
exit
```

---

## Contribuindo  

1. **Fork** o repositório.  
2. Crie uma **branch** para sua feature ou correção:

```bash
git checkout -b feature/minha-feature
```

3. **Commit** com mensagens claras (conforme o padrão Conventional Commits).  
4. **Push** e abra um **Pull Request**.  
5. O CI rodará os testes; após aprovação, o PR será mesclado.

### Guia de Estilo  

- **PHP** – PSR‑12.  
- **Blade** – identação de 4 espaços, uso de componentes (`<x-navbar/>`, `<x-footer/>`).  
- **CSS** – Preferência por utilitários Bootstrap; customizações apenas em `public/css/*.css`.  
- **JavaScript** – ES6+, módulos, sem jQuery quando possível.

---

## Roadmap  

| Versão | Meta | Status |
|-------|------|--------|
| **1.0** | Lançamento MVP (produtos, eventos, página institucional) | ✅ Produção |
| **1.1** | Sistema de carrinho e checkout (integrado a PagSeguro) | 🚧 Em desenvolvimento |
| **1.2** | Painel de administração avançado (estatísticas, upload em lote) | 📋 Planejado |
| **2.0** | PWA (instalação offline, push notifications) | 📋 Planejado |

---

## Licença  

Distribuído sob a licença **MIT**. Consulte o arquivo `LICENSE` para mais detalhes.

---

## Agradecimentos  

- **Laravel** – framework que permitiu construir tudo de forma rápida e segura.  
- **Bootstrap** – pelos utilitários de UI que mantêm o visual premium e responsivo.  
- **Design da Associação** – cores e identidade visual fornecidas pelos artesãos.  
- **Comunidade Open‑Source** – pelos pacotes (`laravel/ui`, `spatie/laravel-medialibrary`, etc.) que facilitaram o desenvolvimento.

---

*Pronto! Basta copiar o conteúdo acima para o `README.md` do projeto. Boa codificação!*
