# ERP API Modular

ERP em Laravel 12 organizado por modulos e exposto apenas como API JSON.

## Arquitetura

Os dominios principais ficam em `app/Modules`:

- `Auth`
- `Product`
- `User`
- `Client`
- `Sale`

Cada modulo segue o padrao Laravel por contexto, usando apenas as pastas necessarias ao dominio:

- `Controllers`
- `Models`
- `Requests`
- `Services`
- `Repositories`
- `Policies`
- `Routes`
- `Tests`

Arquivos compartilhados e de infraestrutura permanecem fora dos modulos quando fazem sentido global, como:

- `app/Http/Controllers/ApiController.php`
- `app/Http/Middleware/ForceJsonResponse.php`
- `app/Providers/AppServiceProvider.php`
- `config/`
- `database/`

## Endpoints

A API principal fica em `routes/web.php` com prefixo:

```bash
/api/v1
```

Rotas carregadas por modulo:

- `app/Modules/Auth/Routes/api.php`
- `app/Modules/Product/Routes/api.php`
- `app/Modules/User/Routes/api.php`
- `app/Modules/Client/Routes/api.php`
- `app/Modules/Sale/Routes/api.php`

## Modulos

### Auth

- login
- logout
- usuario autenticado
- recuperacao de senha

### Product

- produtos
- categorias
- fornecedores
- catalogo publico
- SEO de produto
- upload e ordenacao de imagens

### User

- CRUD de usuarios
- permissoes por nivel de acesso
- reset de senha
- ativacao e desativacao

### Client

- CRUD de clientes
- endereco vinculado ao cliente

### Sale

- CRUD de vendas
- itens de venda
- totalizacao automatica

## Instalcao

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Testes

```bash
php artisan test
```

Os testes ficam distribuidos por modulo dentro de `app/Modules/*/Tests/Feature`.

## Observacoes

- o projeto foi limpo para operar como API-only
- o frontend Inertia/Vue legado foi removido do fluxo principal
- o retorno dos endpoints e padronizado em JSON
