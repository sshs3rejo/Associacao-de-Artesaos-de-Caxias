# Associação dos Artesãos de Caxias

> Plataforma web institucional, administrativa e de gerenciamento de pedidos de artesanato da Associação de Artesãos de Caxias (MA).
>
> **Centro Universitário de Ciências e Tecnologia do Maranhão – UniFacema**
> **Curso:** Tecnologia em Análise e Desenvolvimento de Sistemas (ADS)
> **Disciplina:** Projeto Integrador Extensionista: Back-end

**Sistema em Produção:** [associacao-de-artesaos-de-caxias-production.up.railway.app](https://associacao-de-artesaos-de-caxias-production.up.railway.app/)

---

## Sumário

1. [Stack Tecnológica](#1-stack-tecnológica)
2. [Setup do Projeto](#2-setup-do-projeto)
3. [Deploy](#3-deploy)
4. [Arquitetura](#4-arquitetura)
5. [Funcionalidades](#5-funcionalidades)
6. [Banco de Dados](#6-banco-de-dados)
7. [Estrutura de Diretórios](#7-estrutura-de-diretórios)
8. [Licença](#8-licença)

---

## 1. Stack Tecnológica

### Back-end
- **PHP 8.2+**
- **Laravel 12.x** — Eloquent ORM, Blade templating, Middleware, Form Requests, Events

### Front-end
- **Tailwind CSS 3.x** — Compilado estaticamente (~35KB), sem Vite/Node em produção
- **Font Awesome 6.7.2** — via CDN com SRI
- **Cropper.js 1.6.2** — via CDN com SRI (corte de imagem client-side)
- **Fonte Outfit** — Self-hosted em `public/fonts/`
- **Vanilla JavaScript** — Transições SPA-like, carrinho (localStorage), validação cliente

### Banco de Dados
- **PostgreSQL 16** hospedado no **Neon** (serverless)
- 34 migrations executadas

### Infraestrutura
- **Railway** — Hosting do container Laravel com SSL automático
- **Neon** — PostgreSQL serverless com autoscaling
- **Railway Volume** — Armazenamento persistente de imagens em `/app/storage/app/public`

---

## 2. Setup do Projeto

### Pré-requisitos
- PHP 8.2+
- Composer
- PostgreSQL 16
- Node.js + npx (apenas para recompilar Tailwind)

### Instalação

```bash
git clone https://github.com/sshs3rejo/Associacao-de-Artesaos-de-Caxias.git
cd "Associação de Artesãos de Caxias"
composer install
cp .env.example .env
php artisan key:generate
```

### Configurar `.env`

```env
APP_URL=http://localhost:8000
APP_NAME="Associação dos Artesãos de Caxias"
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=associacao_artesaos
DB_USERNAME=postgres
DB_PASSWORD=seu-password
```

### Criar banco PostgreSQL

```bash
createdb associacao_artesaos
# ou via psql:
# CREATE DATABASE associacao_artesaos;
```

### Migrations

```bash
php artisan migrate --force
```

### Storage Link

```bash
php artisan storage:link
```

> O symlink `public/storage` → `storage/app/public` é necessário para servir imagens. Em produção (Railway) ele é criado automaticamente no `AppServiceProvider::boot()`.

### Criar usuário admin

```bash
php artisan tinker
> User::create([
>     'name' => 'Admin',
>     'email' => 'admin@admin.com',
>     'password' => bcrypt('admin123'),
>     'role' => 'admin',
>     'is_active' => true,
> ]);
```

> **Credenciais padrão (produção):** `admin@admin.com` / `admin123`

### Compilar assets (opcional)

O Tailwind é pré-compilado. Para recompilar após alterações:

```bash
npx tailwindcss -i resources/css/app.css -o public/css/tailwind.css --minify
```

### Servir localmente

```bash
php artisan serve
```

Acessar `http://localhost:8000`

### Otimização (produção)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 3. Deploy

O deploy é feito via **Railway** conectado ao repositório GitHub, usando **Nixpacks** como builder.

### Configuração Railway

```json
// railway.json
{
  "build": {
    "builder": "NIXPACKS"
  }
}
```

### Configuração PHP (Nixpacks)

```toml
# nixpacks.toml
[php]
uploadMaxFilesize = "100M"
postMaxSize = "100M"
```

### Variáveis de Ambiente (Produção)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://associacao-de-artesaos-de-caxias-production.up.railway.app

DB_CONNECTION=pgsql
DB_HOST=ep-<projeto>.c<id>.<região>.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=<password>
DB_SSLMODE=require
```

**⚠️ Use endpoint direto do Neon** (sem `-pooler`) para evitar erro `25P02` em transações.

### Volume Railway (imagens persistentes)

Um **Volume Railway** de 500MB é montado em `/app/storage/app/public` para persistir imagens entre deploys. Configurar no dashboard Railway:
1. Abrir o projeto → **Volumes** → **Add Volume**
2. Mount path: `/app/storage/app/public`
3. Tamanho: 500MB (gratuito)

### Comandos pós-deploy

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 4. Arquitetura

```
                    ┌─────────────────────────┐
                    │       Navegador         │
                    │  + Cropper.js (crop)    │
                    │  + Canvas (compressão)  │
                    └────────────┬────────────┘
                                 │ Requisição HTTP
                                 ▼
                    ┌─────────────────────────┐
                    │  Roteador (web.php)     │
                    │  + Middleware (auth,    │
                    │    admin, artisan,      │
                    │    throttle)            │
                    └────────────┬────────────┘
                                 │
                                 ▼
                    ┌─────────────────────────┐
                    │  Form Request (validação)│
                    └────────────┬────────────┘
                                 │
                                 ▼
                    ┌─────────────────────────┐
                    │       Controller        │
                    └────────────┬────────────┘
                     ▲          │           ▲
                     │          │           │
        Acessa/Modifica │          │           │ View
                     │          │           │ (Blade)
                     ▼          ▼           │
              ┌──────────┐ ┌──────────┐ ┌───┴──────┐
              │  Model   │ │  BD      │ │  View    │
              │ (Eloquent│ │(Postgres)│ │ (HTML,   │
              │  ORM)    │ │          │ │  CSS, JS)│
              └──────────┘ └──────────┘ └──────────┘
```

### Fluxo de Upload de Imagem

1. Usuário seleciona foto no formulário de produto
2. **Cropper.js** abre modal de corte (corte livre, sem aspect ratio fixo)
3. Usuário ajusta enquadramento com drag + zoom (pinça no mobile)
4. Ao confirmar, `cropper.getCroppedCanvas()` gera canvas cortado
5. Canvas exportado como JPEG (qualidade 0.85, max 1600px)
6. Arquivo substitui o `<input>` original via `DataTransfer`
7. Formulário envia `multipart/form-data` normalmente
8. Controller salva via `$request->file('imagem')->store('produtos', 'public')`
9. Arquivo fica em `storage/app/public/produtos/` (persistente via Volume Railway)
10. Vitrine renderiza com `asset('storage/' . $produto->imagem)`

### Segurança

- **CSRF**: Todas as rotas POST/PUT/DELETE exigem token (`<meta name="csrf-token">` no layout)
- **Throttle**: `5/10min` contato, `20/1min` auth, `30/1min` artisan, `60/1min` admin
- **Middleware por role**: `admin`, `artisan`, `auth`
- **Senhas**: `Hash::make()` com BCrypt
- **Contas inativas**: Bloqueadas no login (`is_active = false`)
- **Form Requests**: 12 classes com validação desacoplada dos controllers

---

## 5. Funcionalidades

### Portal Público
- Vitrine de produtos com busca por nome/descrição
- Filtro por categoria (lista plana)
- Agenda de eventos públicos
- Perfil público do artesão
- Carrinho de compras com localStorage + checkout
- Inscrição em eventos com controle de vagas
- Formulário de contato

### Painel do Artesão
- Dashboard com vendas dos próprios produtos
- Gerenciamento de perfil público (bio, foto, redes sociais)
- Proposição de novos produtos (pendente de aprovação) com crop de imagem integrado
- Proposição de novos eventos (pendente de aprovação)
- CRUD de produtos e eventos próprios

### Painel Administrativo
- Dashboard com estatísticas em tempo real
- Aprovação/rejeição de artesãos, produtos e eventos
- Gestão de usuários (CRUD, roles, ativar/desativar)
- Gestão de clientes
- Gestão de vendas (confirmar pagamento)
- Gestão de categorias (lista plana, sem hierarquia)
- Gestão de instrutores, fornecedores, matérias-primas
- Gestão de compras, oficinas, inscrições
- Configurações da associação (persistidas em BD)
- Activity log com filtro por ação
- Atribuição de artesão ao produto + opção "Mostrar artesão na página pública"

### Performance
- Cache de categorias (`getAllCached()` — 3600s)
- Cache de eventos públicos (`300s`)
- Cache de configurações (`3600s`)
- Cache do dashboard (`60s`)
- Compressão client-side de imagens (Canvas API, max 1600px, JPEG 0.85)
- N+1 eliminado em todas as queries
- Transações com `DB::transaction()` em operações críticas

---

## 6. Banco de Dados

34 migrations, PostgreSQL.

### Principais Tabelas

| Tabela | Descrição |
|--------|-----------|
| `users` | Usuários do sistema (admin, artisan, user) |
| `artisan_profiles` | Perfil público do artesão (1:1 com users) |
| `produto` | Produtos cadastrados (com `mostrar_artesao` boolean, `id_artesan` FK, `imagem` path) |
| `_estoques` | Estoque por produto (1:1) |
| `categorias_produtos` | Categorias planas (sem `parent_id`) |
| `_vendas` | Pedidos |
| `itens_venda` | Itens do pedido |
| `_eventos` | Eventos/workshops |
| `_inscricoes_evento` | Inscrições em eventos |
| `_oficina` | Oficinas |
| `_inscricoes_oficina` | Inscrições em oficinas |
| `_cliente` | Clientes |
| `_fornecedores` | Fornecedores |
| `materias_primas` | Matérias-primas |
| `compras_materia_prima` | Compras de matéria-prima (pivot com data/qtd/preço) |
| `instrutores` | Instrutores |
| `contatos` | Submissões do formulário de contato |
| `settings` | Configurações da associação (chave-valor com cache) |
| `activity_logs` | Registro de atividades (MorphTo polimórfico) |

### Casts (tipagem forte)

Todas as models possuem `$casts` definidos:
- `boolean`: `is_approved`, `is_active`, `lido`, `is_public`, `mostrar_artesao`
- `date`: `data_venda`, `data_compra`
- `datetime`: `data_inicio`, `data_fim`, `data_inscricao`
- `decimal`: `preco`, `valor_total`, `preco_unitario`, `carga_horaria`
- `integer`: `quantidade`, `vagas`

### Soft Deletes

Models: `User`, `Produto`, `Vendas`, `Eventos`, `InscricoesEvento`

---

## 7. Estrutura de Diretórios

```
app/
├── Http/
│   ├── Controllers/        # 21 controllers
│   │   ├── Admin/          # 12 admin controllers
│   │   └── ...             # Controllers públicos
│   ├── Requests/           # 12 Form Requests
│   └── Middleware/         # CheckAdmin, CheckArtisan
├── Models/                 # 19 Eloquent models
├── Observers/              # CategoriaProdutosObserver
└── Providers/              # AppServiceProvider
resources/
├── views/                  # ~50 Blade views
│   ├── admin/              # Views do painel admin
│   ├── artesan/            # Views do painel artesão
│   ├── components/         # 14 Blade components
│   ├── layouts/            # Layout principal
│   └── auth/               # Login
public/
├── css/tailwind.css        # Tailwind compilado (~35KB)
├── css/app.css             # CSS customizado
├── js/cart.js              # Carrinho (localStorage)
├── js/app.js               # SPA-like navigation
└── fonts/                  # Outfit (self-hosted)
database/
└── migrations/             # 34 migrations
routes/
└── web.php                 # ~60 rotas
```

---

## 8. Licença

MIT — Projeto acadêmico.
