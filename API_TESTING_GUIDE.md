# API Testing Guide - Hoppscotch

## 🚀 Configuração Hoppscotch

### 1. Importar Collection
1. Abra [Hoppscotch](https://hoppscotch.io)
2. Clique em **Import**
3. Selecione **Raw Text**
4. Cole o conteúdo do arquivo `hoppscotch-collection.json`
5. Clique em **Import**

### 2. Configurar Variáveis
Na collection, configure as variáveis:
- `baseUrl`: `http://127.0.0.1:8000` (ou seu servidor)
- `token`: (deixe vazio, será preenchido após login)

## 📋 Fluxo de Testes

### 🔐 Autenticação
1. **POST** `/api/v1/auth/login`
   ```json
   {
     "email": "admin@teste.com",
     "password": "Mudar@123",
     "device_name": "hoppscotch_test"
   }
   ```
2. Copie o token da resposta
3. Cole na variável `token` da collection

### 📦 Produtos
1. **GET** `/api/v1/products/form-options` - Listar categorias/fornecedores
2. **POST** `/api/v1/products` - Criar produto
3. **GET** `/api/v1/products` - Listar produtos
4. **GET** `/api/v1/products/1` - Ver produto específico
5. **PUT** `/api/v1/products/1` - Atualizar produto
6. **DELETE** `/api/v1/products/1` - Remover produto

### 👥 Usuários
1. **GET** `/api/v1/users` - Listar usuários (admin only)
2. **POST** `/api/v1/users` - Criar usuário (admin only)

### 🏭 Catálogo Público (sem autenticação)
1. **GET** `/api/v1/catalog/home` - Página inicial
2. **GET** `/api/v1/catalog/products` - Produtos da loja
3. **GET** `/api/v1/catalog/products/slug` - Detalhes do produto

## 🧪 Testes PHPUnit

### Executar todos os testes API
```bash
php artisan test tests/Feature/Api/
```

### Executar teste específico
```bash
# Autenticação
php artisan test tests/Feature/Api/AuthApiTest.php

# Produtos
php artisan test tests/Feature/Api/ProductApiTest.php

# Usuários
php artisan test tests/Feature/Api/UserApiTest.php

# Catálogo Público
php artisan test tests/Feature/Api/CatalogApiTest.php

# Segurança
php artisan test tests/Feature/Api/SecurityApiTest.php

# Validação
php artisan test tests/Feature/Api/ValidationApiTest.php
```

### Com Coverage
```bash
php artisan test tests/Feature/Api/ --coverage
```

## 🔍 Testes Criados

### 1. AuthApiTest.php
- ✅ Login com credenciais válidas
- ✅ Login com credenciais inválidas
- ✅ Logout (revogar token)

### 2. ProductApiTest.php
- ✅ Listar produtos (autenticado)
- ✅ Bloquear acesso não autenticado
- ✅ Criar produto (admin)
- ✅ Validação de permissões

### 3. UserApiTest.php
- ✅ Listar usuários (admin only)
- ✅ Bloquear usuário regular
- ✅ Criar usuário (admin)
- ✅ Validação de permissões

### 4. CatalogApiTest.php
- ✅ Acesso público ao catálogo
- ✅ Filtros de produtos
- ✅ Detalhes de produtos públicos
- ✅ Produtos inativos não visíveis

### 5. SecurityApiTest.php
- ✅ Bloqueio de requisições não autenticadas
- ✅ Validação de tokens inválidos
- ✅ Proteção XSS nas respostas
- ✅ Sanitização de entrada
- ✅ Rate limiting

### 6. ValidationApiTest.php
- ✅ Validação de login
- ✅ Validação de produtos
- ✅ Validação de usuários
- ✅ Formato de erros

## 🎯 Cenários de Teste Hoppscotch

### ✅ Cenários Positivos
1. Login → Obter token → Listar produtos
2. Criar produto → Atualizar → Deletar
3. Acesso catálogo público sem token

### ❌ Cenários Negativos
1. Acesso endpoints sem token (401)
2. Token inválido (401)
3. Usuário regular acessando admin (403)
4. Dados inválidos nos formulários (422)

### 🔒 Cenários de Segurança
1. XSS em campos de entrada
2. HTML injection
3. Rate limiting
4. Token reuse

## 📊 Resumo

**Hoppscotch:** Testes manuais, documentação, desenvolvimento
**PHPUnit:** Testes automatizados, CI/CD, qualidade

**Use ambos para demonstrar conhecimento completo!** 🚀
