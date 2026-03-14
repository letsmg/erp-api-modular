<img src="https://raw.githubusercontent.com/letsmg/erp-vue-laravel/main/pacman-contribution-graph.svg" />

# 🌌 ERP Vue Laravel — Smart Business Management

> Sistema moderno de gestão empresarial (ERP) construído com **Laravel + Vue**, focado em **performance, segurança e experiência do desenvolvedor (DX)**.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?style=for-the-badge&logo=postgresql)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css)

---

# 🌎 Language / Idioma

- 🇧🇷 [Ver em Português](#-português)
- 🇺🇸 [Read in English](#-english)

---

# 🇧🇷 Português

# 📦 Visão Geral

ERP Vue Laravel é um ERP moderno projetado para entregar:

- ⚡ Alta performance
- 🔒 Segurança robusta
- 🧠 Excelente experiência para desenvolvedores
- 🧩 Arquitetura modular
- 🚀 Desenvolvimento rápido usando Inertia.js

O projeto foca em **simplicidade sem perder escalabilidade**.

---

# 🧰 Tecnologias

| Camada | Tecnologia |
|------|------------|
| Backend | Laravel 11 (PHP 8.2+) |
| Frontend | Vue 3 (Composition API) |
| Build Tool | Vite |
| Comunicação | Inertia.js |
| Estilização | Tailwind CSS |
| Icons | Lucide Vue |

---

# ⚡ Experiência do Desenvolvedor (DX)

Para acelerar desenvolvimento e testes, o sistema possui utilitários globais de formulário.

## Atalhos de Teclado

| Atalho | Ação |
|------|------|
| CTRL + SHIFT + P | Preenche formulário com dados fictícios |
| CTRL + SHIFT + L | Limpa campos e erros de validação |

TIP  
Esses atalhos utilizam **Custom Events** disparados dentro do `AuthenticatedLayout.vue`, mantendo a lógica das páginas limpa.

---

# 🚀 Instalação

## 1. Clonar o repositório

```bash
git clone https://github.com/letsmg/erp-vue-laravel.git
cd erp-vue-laravel
```

---

## 2. Instalar dependências

### PHP

```bash
composer install
```

### JavaScript

```bash
npm install
npm run dev
```

---

## 3. Configurar ambiente

Copie o arquivo `.env`

```bash
cp .env.example .env
```

Configure o banco:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=erp_vue_laravel
DB_USERNAME=postgres
DB_PASSWORD=123456
```

NOTE  
Certifique-se de que as extensões **pdo_pgsql** e **pgsql** estão ativas no `php.ini`.

---

## 4. Rodar migrações

```bash
php artisan migrate --seed
```

Isso criará a estrutura do banco e o **usuário administrador inicial**.

---

# ⚙️ Arquitetura

## Inertia vs API REST

O projeto utiliza **Controllers híbridos com Inertia.js** ao invés de uma pasta `/api`.

### Vantagens

- Não necessita API separada
- Proteção CSRF nativa
- Desenvolvimento mais rápido
- Estado compartilhado entre backend e frontend

### API futura

Caso seja necessário expor API pública:

- A lógica pode ser extraída para **Services**
- Controllers podem expor endpoints via **Laravel Sanctum**
- Total reaproveitamento do código

---

# 🔒 Segurança e Performance

## Autenticação

Sistema de autenticação customizado com:

- Hash Argon2id
- Memory cost: 64MB
- Threads: 2

---

## SEO

A tabela de produtos possui suporte para:

- slug
- seo_title
- seo_keywords

Com geração automática de **URLs amigáveis**.

---

## UX Reativa

Funcionalidades implementadas:

- Busca em tempo real com debounce
- Filtros rápidos no módulo de fornecedores

---

# 🤖 Moderação de Imagens com IA (Opcional)

O sistema pode integrar com **Google Cloud Vision** para detectar imagens impróprias durante upload.

Se conteúdo inadequado for detectado, o upload será bloqueado.

---

# 📦 Módulos

- [x] Gestão de Usuários
- [x] Fornecedores
- [ ] Produtos
- [ ] Vendas

---

# 🇺🇸 English

# 📦 Overview

ERP Vue Laravel is a modern ERP designed to deliver:

- ⚡ High performance
- 🔒 Robust security
- 🧠 Excellent developer experience
- 🧩 Modular architecture
- 🚀 Rapid development using Inertia.js

The project focuses on **simplicity without sacrificing scalability**.

---

# 🧰 Tech Stack

| Layer | Technology |
|------|------------|
| Backend | Laravel 11 (PHP 8.2+) |
| Frontend | Vue 3 (Composition API) |
| Build Tool | Vite |
| Communication | Inertia.js |
| Styling | Tailwind CSS |
| Icons | Lucide Vue |

---

# ⚡ Developer Experience (DX)

To accelerate development and testing, the system includes global form utilities.

## Keyboard Shortcuts

| Shortcut | Action |
|------|------|
| CTRL + SHIFT + P | Populate form with fake data |
| CTRL + SHIFT + L | Clear form fields and validation errors |

These shortcuts use **Custom Events** triggered inside `AuthenticatedLayout.vue`.

---

# 🚀 Installation

## 1. Clone repository

```bash
git clone https://github.com/letsmg/erp-vue-laravel.git
cd erp-vue-laravel
```

---

## 2. Install dependencies

### PHP

```bash
composer install
```

### JavaScript

```bash
npm install
npm run dev
```

---

## 3. Configure environment

```bash
cp .env.example .env
```

Database example:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=erp_vue_laravel
DB_USERNAME=postgres
DB_PASSWORD=123456
```

---

## 4. Run migrations

```bash
php artisan migrate --seed
```

This will create the database structure and generate the **initial admin user**.

---

# 🔒 Security

Authentication system using:

- Argon2id hashing
- Memory cost: 64MB
- Threads: 2

---

# 📄 License

MIT License

---

<p align="center">
<strong>ERP Vue Laravel — Technology for Smart Business</strong>
</p>

<p align="center">
© 2026 — Built with scalability in mind
</p>

<img src="https://raw.githubusercontent.com/letsmg/erp-vue-laravel/main/snake-dark.svg?palette=github-dark" />