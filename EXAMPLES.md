# Exemplos de Uso da API

## Cenário 1: Aplicativo Móvel sincronizando dados

### 1. Login no aplicativo
```bash
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@financas.com",
    "password": "123456"
  }'
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

### 2. Aplicativo envia dados locais para o servidor
```bash
curl -X POST http://localhost:8080/api/financas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng==" \
  -d '{
    "receitas": [
      {
        "id": 1748885048554,
        "descricao": "Salário",
        "valor": 3000,
        "data": "2025-06-02"
      },
      {
        "id": 1748886429013,
        "descricao": "Freelance",
        "valor": 500,
        "data": "2025-06-01"
      }
    ],
    "despesas": [
      {
        "id": 1748886107034,
        "descricao": "Uber para trabalho",
        "categoria": "transporte",
        "valor": 25.50,
        "data": "2025-06-02"
      },
      {
        "id": 1748886397199,
        "descricao": "Almoço",
        "categoria": "alimentacao",
        "valor": 18.90,
        "data": "2025-06-02"
      }
    ],
    "planejamento": {
      "receitas": [
        {
          "id": 1748884007296,
          "descricao": "Projeto Website",
          "valor": 1500
        }
      ],
      "despesas": [
        {
          "id": 1748884316833,
          "descricao": "Curso de Python",
          "categoria": "educacao",
          "valorTotal": 400,
          "valor": 40,
          "parcelada": true,
          "numParcelas": 10,
          "parcelaAtual": 1,
          "taxaJuros": 0
        }
      ]
    }
  }'
```

## Cenário 2: Computador recuperando dados sincronizados

### 1. Login no computador
```bash
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@financas.com",
    "password": "123456"
  }'
```

### 2. Baixar todos os dados
```bash
curl -X GET http://localhost:8080/api/financas \
  -H "Authorization: Bearer YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng=="
```

### 3. Salvar em arquivo local (exemplo)
```bash
curl -X GET http://localhost:8080/api/financas \
  -H "Authorization: Bearer YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng==" \
  | jq '.data' > financas_backup.json
```

## Cenário 3: Aplicativo adicionando nova transação

### 1. Adicionar receita pontual
```bash
curl -X POST http://localhost:8080/api/receitas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng==" \
  -d '{
    "descricao": "Venda produto usado",
    "valor": 150.00,
    "data": "2025-06-02"
  }'
```

### 2. Adicionar despesa pontual
```bash
curl -X POST http://localhost:8080/api/despesas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng==" \
  -d '{
    "descricao": "Combustível",
    "categoria": "transporte",
    "valor": 80.00,
    "data": "2025-06-02"
  }'
```

## Cenário 4: Gerenciamento de planejamento

### 1. Adicionar receita planejada
```bash
curl -X POST http://localhost:8080/api/planejamento/receitas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng==" \
  -d '{
    "descricao": "Bonus anual esperado",
    "valor": 2000.00
  }'
```

### 2. Adicionar despesa planejada (parcelada)
```bash
curl -X POST http://localhost:8080/api/planejamento/despesas \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YWRtaW5AZmluYW5jYXMuY29tOjEyMzQ1Ng==" \
  -d '{
    "descricao": "Notebook novo",
    "categoria": "outros",
    "valorTotal": 2500.00,
    "valor": 220.83,
    "parcelada": true,
    "numParcelas": 12,
    "parcelaAtual": 1,
    "taxaJuros": 0.025
  }'
```

## Códigos de Exemplo para Integração

### JavaScript (Frontend/Mobile)
```javascript
class FinancasAPI {
  constructor(baseUrl) {
    this.baseUrl = baseUrl;
    this.token = localStorage.getItem('finance_token');
  }

  async login(email, password) {
    const response = await fetch(`${this.baseUrl}/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password })
    });
    
    const data = await response.json();
    if (data.token) {
      this.token = data.token;
      localStorage.setItem('finance_token', data.token);
    }
    return data;
  }

  async getAllData() {
    const response = await fetch(`${this.baseUrl}/financas`, {
      headers: { 'Authorization': `Bearer ${this.token}` }
    });
    return response.json();
  }

  async syncData(financialData) {
    const response = await fetch(`${this.baseUrl}/financas`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${this.token}`
      },
      body: JSON.stringify(financialData)
    });
    return response.json();
  }

  async addReceita(receita) {
    const response = await fetch(`${this.baseUrl}/receitas`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${this.token}`
      },
      body: JSON.stringify(receita)
    });
    return response.json();
  }
}

// Uso
const api = new FinancasAPI('http://localhost:8080/api');
await api.login('admin@financas.com', '123456');
const dados = await api.getAllData();
```

### Python (Script de Backup)
```python
import requests
import json
from datetime import datetime

class FinancasAPI:
    def __init__(self, base_url):
        self.base_url = base_url
        self.token = None

    def login(self, email, password):
        response = requests.post(f"{self.base_url}/login", 
                               json={"email": email, "password": password})
        data = response.json()
        if 'token' in data:
            self.token = data['token']
        return data

    def get_all_data(self):
        headers = {"Authorization": f"Bearer {self.token}"}
        response = requests.get(f"{self.base_url}/financas", headers=headers)
        return response.json()

    def backup_to_file(self, filename=None):
        if not filename:
            filename = f"backup_financas_{datetime.now().strftime('%Y%m%d_%H%M%S')}.json"
        
        data = self.get_all_data()
        with open(filename, 'w', encoding='utf-8') as f:
            json.dump(data['data'], f, indent=2, ensure_ascii=False)
        
        print(f"Backup salvo em: {filename}")
        return filename

# Uso
api = FinancasAPI('http://localhost:8080/api')
api.login('admin@financas.com', '123456')
api.backup_to_file()
```

## Segurança

### Headers necessários em todas as requisições autenticadas:
```
Authorization: Bearer {token}
Content-Type: application/json
```

### Tratamento de erros:
```javascript
async function safeApiCall(apiFunction) {
  try {
    const result = await apiFunction();
    if (result.error) {
      console.error('Erro da API:', result.error);
      return null;
    }
    return result;
  } catch (error) {
    console.error('Erro de rede:', error);
    return null;
  }
}
```
