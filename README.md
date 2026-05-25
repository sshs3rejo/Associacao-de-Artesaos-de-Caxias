# Associação dos Artesãos de Caxias

> Plataforma web institucional, administrativa e de gerenciamento de pedidos de artesanato da Associação de Artesãos de Caxias (MA).
>
> **Faculdade UniFacema**
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
- **Fonte Outfit** — Self-hosted em `public/fonts/`
- **Vanilla JavaScript** — Transições SPA-like, carrinho (localStorage), validação cliente

### Banco de Dados
- **PostgreSQL 16** hospedado no **Neon** (serverless)
- 32 migrations executadas

### Infraestrutura
- **Railway** — Hosting do container Laravel com SSL automático
- **Neon** — PostgreSQL serverless com autoscaling

---

## 2. Setup do Projeto

### Pré-requisitos
- PHP 8.2+
- Composer
- PostgreSQL 16

### Instalação

```bash
git clone <repo-url>
cd "Associação de Artesãos de Caxias"
composer install
cp .env.example .env
php artisan key:generate
```

### Configurar `.env`

```env
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=associacao_artesaos
DB_USERNAME=postgres
DB_PASSWORD=seu-password
```

### Migrations + Seed

```bash
php artisan migrate --force
```

> **Nota:** O sistema roda **sem seeders**. O primeiro usuário admin deve ser criado manualmente via Tinker:
> ```bash
> php artisan tinker
> > User::create(['name' => 'Admin', 'email' => 'admin@associacao.com', 'password' => bcrypt('senha123'), 'role' => 'admin', 'is_active' => true]);
> ```

### Assets (sem Node)

O Tailwind é pré-compilado. Para recompilar:

```bash
npx tailwindcss -i resources/css/app.css -o public/css/tailwind.css --minify
```

### Servir

```bash
php artisan serve
```

Acessar `http://localhost:8000`

---

## 3. Deploy

O deploy é feito via **Railway** conectado ao repositório GitHub.

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

### Comando pós-deploy

```bash
php artisan migrate --force
```

---

## 4. Arquitetura

```
                    ┌─────────────────────────┐
                    │       Navegador         │
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

### Segurança

- **CSRF**: Todas as rotas POST/PUT/DELETE exigem token
- **Throttle**: `5/10min` contato, `20/1min` auth, `30/1min` artisan, `60/1min` admin
- **Middleware por role**: `admin`, `artisan`, `auth`
- **Senhas**: `Hash::make()` com BCrypt
- **Contas inativas**: Bloqueadas no login (`is_active = false`)
- **Form Requests**: 11 classes com validação desacoplada dos controllers

---

## 5. Funcionalidades

### Portal Público
- Vitrine de produtos com busca por nome/descrição
- Filtro por categoria (hierárquico)
- Agenda de eventos públicos
- Perfil público do artesão
- Carrinho de compras com localStorage + checkout
- Inscrição em eventos com controle de vagas
- Formulário de contato

### Painel do Artesão
- Dashboard com vendas dos próprios produtos
- Gerenciamento de perfil público (bio, foto, redes sociais)
- Proposição de novos produtos (pendente de aprovação)
- Proposição de novos eventos (pendente de aprovação)
- CRUD de produtos e eventos próprios

### Painel Administrativo
- Dashboard com estatísticas em tempo real
- Aprovação/rejeição de artesãos, produtos e eventos
- Gestão de usuários (CRUD, roles, ativar/desativar)
- Gestão de clientes
- Gestão de vendas (confirmar pagamento)
- Gestão de categorias (hierárquicas)
- Gestão de instrutores, fornecedores, matérias-primas
- Gestão de compras, oficinas, inscrições
- Configurações da associação (persistidas em BD)
- Activity log com filtro por ação

### Performance
- Cache das categorias (`3600s`)
- Cache hierárquico das categorias (`3600s`)
- Cache de eventos públicos (`300s`)
- Cache de configurações (`3600s`)
- Cache do dashboard (`60s`)
- N+1 eliminado em todas as queries
- Transações com `DB::transaction()` em operações críticas

---

## 6. Banco de Dados

32 migrations, PostgreSQL.

### Principais Tabelas

| Tabela | Descrição |
|--------|-----------|
| `users` | Usuários do sistema (admin, artisan, user) |
| `artisan_profiles` | Perfil público do artesão (1:1 com users) |
| `produto` | Produtos cadastrados |
| `_estoques` | Estoque por produto (1:1) |
| `categorias_produtos` | Categorias hierárquicas (parent_id FK auto-referencial) |
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
- `boolean`: `is_approved`, `is_active`, `lido`, `is_public`
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
│   ├── Requests/           # 11 Form Requests
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
└── migrations/             # 32 migrations
routes/
└── web.php                 # ~60 rotas
```

---

## 8. Licença

MIT — Projeto acadêmico.
