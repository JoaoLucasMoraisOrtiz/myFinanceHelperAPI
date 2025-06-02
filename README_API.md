# API de Finanças Pessoais

Uma API RESTful segura para gerenciamento de dados financeiros pessoais, desenvolvida em Laravel. Permite sincronização de dados entre dispositivos móveis e computadores com autenticação robusta.

## 🚀 Características

- **Autenticação segura** com tokens baseados em credenciais
- **Sincronização completa** de dados financeiros
- **Gestão de receitas e despesas** com categorização
- **Planejamento financeiro** com suporte a parcelas e juros
- **API RESTful** com endpoints bem definidos
- **CORS habilitado** para integração com aplicações frontend
- **Validação de dados** em todos os endpoints
- **Isolamento por usuário** - cada usuário acessa apenas seus dados

## 📊 Estrutura de Dados

A API gerencia três tipos principais de dados:

### Receitas
```json
{
  "id": 1748885048554,
  "descricao": "Salário",
  "valor": 3000.00,
  "data": "2025-06-02"
}
```

### Despesas
```json
{
  "id": 1748886107034,
  "descricao": "Mercado",
  "categoria": "alimentacao",
  "valor": 150.50,
  "data": "2025-06-02"
}
```

### Planejamento
```json
{
  "receitas": [...],
  "despesas": [
    {
      "id": 1748884316833,
      "descricao": "Câmera",
      "categoria": "outros",
      "valorTotal": 3500.00,
      "valor": 310.97,
      "parcelada": true,
      "numParcelas": 12,
      "parcelaAtual": 1,
      "taxaJuros": 0.01
    }
  ]
}
```

## 🛠️ Instalação e Configuração

### Pré-requisitos
- PHP 8.2+
- Composer
- SQLite (padrão) ou MySQL/PostgreSQL

### Passos de Instalação

1. **Instalar dependências:**
```bash
composer install
```

2. **Configurar ambiente:**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configurar banco de dados:**
```bash
# Para SQLite (padrão - não requer configuração adicional)
touch database/database.sqlite

# Para MySQL, edite o .env:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=financas_api
# DB_USERNAME=seu_usuario
# DB_PASSWORD=sua_senha
```

4. **Executar migrações e seeders:**
```bash
php artisan migrate:fresh --seed
```

5. **Iniciar servidor:**
```bash
php artisan serve --host=0.0.0.0 --port=8080
```

A API estará disponível em: `http://localhost:8080/api/`

## 🔐 Autenticação

### Fazer Login
```bash
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@financas.com","password":"123456"}'
```

### Usar Token nas Requisições
```bash
curl -X GET http://localhost:8080/api/financas \
  -H "Authorization: Bearer {token_recebido}"
```

### Usuários de Teste
- **Email:** `admin@financas.com` | **Senha:** `123456`
- **Email:** `user@test.com` | **Senha:** `password`

## 📚 Endpoints Principais

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `POST` | `/api/login` | Autenticação |
| `GET` | `/api/financas` | Obter todos os dados |
| `POST` | `/api/financas` | Sincronizar dados completos |
| `GET` | `/api/receitas` | Listar receitas |
| `POST` | `/api/receitas` | Adicionar receita |
| `GET` | `/api/despesas` | Listar despesas |
| `POST` | `/api/despesas` | Adicionar despesa |
| `GET` | `/api/planejamento` | Obter planejamento |

> **📖 Documentação completa:** Veja [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

## 🧪 Testes

### Teste Manual Rápido
```bash
# Executar script de teste completo
chmod +x test_api.sh
./test_api.sh
```

### Teste Individual
```bash
# Login
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@financas.com","password":"123456"}'

# Obter dados (substitua o token)
curl -X GET http://localhost:8080/api/financas \
  -H "Authorization: Bearer YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng=="
```

## 💡 Casos de Uso

### 1. Aplicativo Móvel → Servidor
O aplicativo móvel envia todos os dados locais para sincronização:
```bash
curl -X POST http://localhost:8080/api/financas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{dados_completos_do_celular}'
```

### 2. Servidor → Computador
O computador baixa os dados sincronizados:
```bash
curl -X GET http://localhost:8080/api/financas \
  -H "Authorization: Bearer {token}" \
  > financas_backup.json
```

### 3. Adição de Transação Individual
```bash
# Nova receita
curl -X POST http://localhost:8080/api/receitas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{"descricao":"Freelance","valor":500,"data":"2025-06-02"}'
```

## 🔒 Segurança

- **Tokens únicos** por usuário baseados em credenciais
- **Validação rigorosa** de entrada em todos os endpoints
- **Isolamento de dados** - usuários só acessam seus próprios dados
- **Senhas criptografadas** no banco de dados
- **CORS configurado** para controle de origem
- **Middleware de autenticação** em todas as rotas protegidas

## 📁 Estrutura do Projeto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php      # Autenticação
│   │   └── FinancasController.php  # CRUD dos dados financeiros
│   └── Middleware/
│       ├── ApiAuthMiddleware.php   # Middleware de autenticação
│       └── CorsMiddleware.php      # Middleware CORS
├── Models/
│   ├── User.php                    # Modelo do usuário
│   └── Financas.php               # Modelo dos dados financeiros
routes/
├── api.php                        # Rotas da API
database/
├── migrations/                    # Migrações do banco
└── seeders/                      # Seeders para dados de teste
```

## 📖 Documentação Adicional

- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - Documentação completa da API
- **[EXAMPLES.md](EXAMPLES.md)** - Exemplos práticos de uso
- **[DEPLOY.md](DEPLOY.md)** - Guia de deploy e configuração para produção

## 🤝 Integração

### JavaScript/Frontend
```javascript
const api = new FinancasAPI('http://localhost:8080/api');
await api.login('admin@financas.com', '123456');
const dados = await api.getAllData();
```

### Python/Scripts
```python
api = FinancasAPI('http://localhost:8080/api')
api.login('admin@financas.com', '123456')
api.backup_to_file()
```

### cURL/Scripts Shell
```bash
TOKEN=$(curl -s -X POST "$API_URL/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@financas.com","password":"123456"}' \
  | jq -r '.token')

curl -X GET "$API_URL/financas" \
  -H "Authorization: Bearer $TOKEN"
```

## 🚀 Deploy

Para produção, consulte o guia completo em [DEPLOY.md](DEPLOY.md).

**Resumo rápido:**
```bash
# Otimizar para produção
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan optimize

# Configurar servidor web (Apache/Nginx)
# Configurar SSL/HTTPS
# Configurar backup automático
```

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 🐛 Suporte

Para problemas ou dúvidas:

1. Verifique os logs: `tail storage/logs/laravel.log`
2. Execute diagnósticos: `php artisan config:show`
3. Consulte a seção de troubleshooting em [DEPLOY.md](DEPLOY.md)

---

**Desenvolvido para gerenciamento seguro e eficiente de finanças pessoais** 💰
