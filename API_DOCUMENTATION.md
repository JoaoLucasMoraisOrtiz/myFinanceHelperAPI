# API de Finanças - Documentação

## Visão Geral

Esta API permite gerenciar dados financeiros pessoais com autenticação segura. Os dados são organizados em:
- **Receitas**: Entradas de dinheiro
- **Despesas**: Gastos realizados
- **Planejamento**: Receitas e despesas planejadas

## Autenticação

A API utiliza autenticação baseada em token. Para todas as requisições (exceto login), inclua o header:

```
Authorization: Bearer {token}
```

### Login

**POST** `/api/login`

```json
{
  "email": "admin@financas.com",
  "password": "123456"
}
```

**Resposta:**
```json
{
  "message": "Login realizado com sucesso",
  "token": "YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng==",
  "user": {
    "id": 1,
    "name": "Admin Financas",
    "email": "admin@financas.com"
  }
}
```

## Usuários de Teste

- **Email:** `admin@financas.com` | **Senha:** `123456`
- **Email:** `user@test.com` | **Senha:** `password`

## Endpoints Principais

### 1. Obter todos os dados financeiros
**GET** `/api/financas`

### 2. Atualizar todos os dados financeiros
**POST** `/api/financas`
```json
{
  "receitas": [...],
  "despesas": [...],
  "planejamento": {
    "receitas": [...],
    "despesas": [...]
  }
}
```

### 3. Receitas

- **GET** `/api/receitas` - Listar receitas
- **POST** `/api/receitas` - Adicionar receita
- **PUT** `/api/receitas/{id}` - Atualizar receita
- **DELETE** `/api/receitas/{id}` - Deletar receita

**Estrutura da Receita:**
```json
{
  "descricao": "Salário",
  "valor": 1000.00,
  "data": "2025-06-02"
}
```

### 4. Despesas

- **GET** `/api/despesas` - Listar despesas
- **POST** `/api/despesas` - Adicionar despesa
- **PUT** `/api/despesas/{id}` - Atualizar despesa
- **DELETE** `/api/despesas/{id}` - Deletar despesa

**Estrutura da Despesa:**
```json
{
  "descricao": "Mercado",
  "categoria": "alimentacao",
  "valor": 150.50,
  "data": "2025-06-02"
}
```

**Categorias disponíveis:**
- `alimentacao`
- `transporte`
- `lazer`
- `saude`
- `educacao`
- `moradia`
- `dividas`
- `outros`

### 5. Planejamento

- **GET** `/api/planejamento` - Obter planejamento completo
- **POST** `/api/planejamento/receitas` - Adicionar receita planejada
- **POST** `/api/planejamento/despesas` - Adicionar despesa planejada
- **PUT** `/api/planejamento/receitas/{id}` - Atualizar receita planejada
- **PUT** `/api/planejamento/despesas/{id}` - Atualizar despesa planejada
- **DELETE** `/api/planejamento/receitas/{id}` - Deletar receita planejada
- **DELETE** `/api/planejamento/despesas/{id}` - Deletar despesa planejada

**Receita Planejada:**
```json
{
  "descricao": "Freelance",
  "valor": 500.00
}
```

**Despesa Planejada:**
```json
{
  "descricao": "Câmera",
  "categoria": "outros",
  "valorTotal": 3500.00,
  "valor": 310.97,
  "parcelada": true,
  "numParcelas": 12,
  "parcelaAtual": 1,
  "taxaJuros": 0.01
}
```

### 6. Validar Token
**GET** `/api/validate-token`

## Códigos de Status

- **200** - Sucesso
- **400** - Dados inválidos
- **401** - Não autorizado (credenciais inválidas ou token ausente)
- **404** - Recurso não encontrado
- **500** - Erro interno do servidor

## Exemplos de Uso

### 1. Login e obtenção de dados (Computador)

```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@financas.com","password":"123456"}'

# Obter dados (substitua o token)
curl -X GET http://localhost:8000/api/financas \
  -H "Authorization: Bearer YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng=="
```

### 2. Sincronização de dados (Celular)

```bash
# Enviar dados completos do celular para o servidor
curl -X POST http://localhost:8000/api/financas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng==" \
  -d '{
    "receitas": [
      {
        "id": 1748885048554,
        "descricao": "salario",
        "valor": 1000,
        "data": "2025-06-02"
      }
    ],
    "despesas": [
      {
        "id": 1748886107034,
        "descricao": "Gasto com uber",
        "categoria": "transporte",
        "valor": 60,
        "data": "2025-06-02"
      }
    ],
    "planejamento": {
      "receitas": [...],
      "despesas": [...]
    }
  }'
```

### 3. Adicionar nova despesa

```bash
curl -X POST http://localhost:8000/api/despesas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng==" \
  -d '{
    "descricao": "Almoço",
    "categoria": "alimentacao",
    "valor": 25.50,
    "data": "2025-06-02"
  }'
```

## Estrutura de Resposta Padrão

**Sucesso:**
```json
{
  "success": true,
  "message": "Operação realizada com sucesso",
  "data": { ... }
}
```

**Erro:**
```json
{
  "error": "Descrição do erro",
  "message": "Detalhes adicionais",
  "messages": { ... } // Para erros de validação
}
```

## Segurança

- Todas as rotas (exceto login) requerem autenticação
- Tokens são baseados em credenciais do usuário
- Cada usuário só acessa seus próprios dados
- Validação de entrada em todos os endpoints
- Senhas são criptografadas no banco de dados

## Como Executar

1. Instalar dependências: `composer install`
2. Configurar `.env` com dados do banco
3. Executar migrações: `php artisan migrate:fresh --seed`
4. Iniciar servidor: `php artisan serve`
5. API disponível em: `http://localhost:8000/api/`
