<img src="https://raw.githubusercontent.com/letsmg/erp_api_modular/main/pacman-contribution-graph.svg" alt="Contribution Graph" style="width: 100%; max-width: 800px; height: auto;"/>

<img src="https://raw.githubusercontent.com/letsmg/erp_api_modular/main/snake.svg" alt="Snake Contribution Graph" />

<img src="https://raw.githubusercontent.com/letsmg/erp_api_modular/main/snake-dark.svg?palette=github-dark" alt="Snake Dark Contribution Graph" />

# ERP API Modular

> ERP em Laravel 12 organizado por módulos com arquitetura Inertia.js + Vue 3 para desenvolvimento rápido e escalável.

## 🏗️ Arquitetura

O sistema utiliza uma abordagem moderna com **Inertia.js** eliminando a necessidade de APIs REST separadas:

### Módulos Principais

Os domínios ficam em `app/Modules`:

- `Auth` - Autenticação e gerenciamento de sessão
- `Product` - Produtos, categorias, fornecedores e SEO
- `User` - Usuários e permissões  
- `Client` - Clientes e endereços
- `Sale` - Vendas e relatórios

### Estrutura dos Módulos

Cada módulo segue o padrão Laravel:

```
app/Modules/[ModuleName]/
├── Controllers/     # Controladores HTTP
├── Models/         # Modelos Eloquent
├── Requests/       # Validações de formulários
├── Services/       # Regras de negócio
├── Repositories/   # Camada de acesso a dados
├── Policies/       # Autorização
├── Routes/         # Definição de rotas
└── Tests/          # Testes automatizados
```

## 🚀 Tecnologias

| Camada      | Tecnologia              |
| ----------- | ----------------------- |
| Backend     | Laravel 12 (PHP 8.3+)   |
| Frontend    | Vue 3 (Composition API) |
| Comunicação | Inertia.js              |
| Estilização | Tailwind CSS            |
| Banco       | PostgreSQL              |
| Build Tool  | Vite                    |

## ⚡ Instalação

### 1. Clonar repositório

```bash
git clone https://github.com/letsmg/erp-api-modular.git
cd erp-api-modular
```

### 2. Instalar dependências

```bash
# PHP
composer install

# Node.js
npm install
```

### 3. Configurar ambiente

```bash
cp .env.example .env
```

Configure o banco de dados:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=erp_api_modular
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

### 4. Rodar migrações

```bash
php artisan migrate --seed
```

### 5. Iniciar desenvolvimento

```bash
# Backend
php artisan serve

# Frontend (em outro terminal)
npm run dev
```

## 🔐 Segurança

### Autenticação
- **Hash Argon2id** com configuração segura
- **Proteção CSRF** nativa do Laravel
- **Sessões** gerenciadas no banco de dados
- **Sanitização** automática de inputs

### Permissões
- Sistema baseado em **níveis de acesso**
- **Policies** para autorização granular
- **Middleware** de proteção de rotas

## 📦 Módulos Implementados

### ✅ Auth
- Login e logout
- Recuperação de senha
- Verificação de usuário autenticado

### ✅ Product
- CRUD completo de produtos
- Gestão de categorias
- Gestão de fornecedores
- SEO avançado (meta tags, schema markup)
- Upload e ordenação de imagens
- **Filtros**: Busca, bloqueados, ativos

### ✅ User
- CRUD de usuários
- Controle de níveis de acesso
- Reset de senha
- Ativação/desativação

### 🚧 Client (Em desenvolvimento)
- CRUD de clientes
- Gestão de endereços vinculados

### 🚧 Sale (Em desenvolvimento)
- CRUD de vendas
- Relatórios de produtos
- Curva ABC
- Geração de Sintegra

## 🧪 Testes

### Executar testes

```bash
# Todos os testes
php artisan test

# Apenas testes de um módulo
php artisan test --filter ProductTest

# Com coverage
php artisan test --coverage
```

### Estrutura dos testes

```
app/Modules/*/Tests/
├── Feature/    # Testes de funcionalidades
└── Unit/       # Testes unitários
```

## 🎯 Funcionalidades Destaque

### 📱 Interface Responsiva
- Design mobile-first
- Componentes reutilizáveis
- Transições suaves

### 🔍 Busca Avançada
- Busca por descrição, marca, modelo
- Filtro por preço
- Paginação com filtros persistentes

### 🖼️ Gestão de Imagens
- Upload via drag-and-drop
- Ordenação visual
- Otimização automática

### 📊 Relatórios
- Relatório de produtos
- Filtros dinâmicos
- Exportação de dados

## 🔧 Desenvolvimento

### Atalhos de Teclado

| Atalho           | Ação                                    |
| ---------------- | --------------------------------------- |
| CTRL + SHIFT + P | Preenche formulário com dados de teste |
| CTRL + SHIFT + L | Limpa campos e erros de validação   |

### Estrutura de Assets

```
resources/js/
├── modules/         # Módulos Vue organizados por domínio
├── shared/          # Componentes e layouts compartilhados
├── lib/             # Utilitários e configurações
└── app.js           # Ponto de entrada da aplicação
```

## 🚀 Deploy

### Produção

```bash
# Otimizar assets
npm run build

# Otimizar autoloader
composer install --optimize-autoloader

# Cache de configuração
php artisan config:cache

# Cache de rotas
php artisan route:cache

# Cache de views
php artisan view:cache
```

## 📝 Contribuição

### Fluxo de trabalho

1. **Fork** o repositório
2. **Branch** com nome descritivo: `feature/nova-funcionalidade`
3. **Commit** com mensagens claras
4. **Push** para o branch
5. **Pull Request** detalhada

### Padrão de commits

```
feat: adiciona nova funcionalidade
fix: corrige bug específico
docs: atualiza documentação
refactor: melhora código sem mudar funcionalidade
test: adiciona ou corrige testes
```

## 📄 Licença

MIT License - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 🤝 Suporte

- **Issues**: [GitHub Issues](https://github.com/letsmg/erp-api-modular/issues)
- **Discussions**: [GitHub Discussions](https://github.com/letsmg/erp-api-modular/discussions)

---

<p align="center">
  <strong>ERP API Modular — Tecnologia para Gestão Inteligente</strong>
</p>

<p align="center">
  © 2026 — Construído com escalabilidade em mente
</p>
