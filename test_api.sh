#!/bin/bash

# Script para testar a API de Finanças
BASE_URL="http://localhost:8080/api"

echo "=== TESTE DA API DE FINANÇAS ==="
echo

# 1. Teste de Login
echo "1. Testando login..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@financas.com","password":"123456"}')

echo "Resposta do login:"
echo "$LOGIN_RESPONSE" | jq .
echo

# Extrair o token da resposta
TOKEN=$(echo "$LOGIN_RESPONSE" | jq -r '.token')

if [ "$TOKEN" = "null" ] || [ -z "$TOKEN" ]; then
    echo "❌ Erro: Não foi possível obter o token de acesso"
    exit 1
fi

echo "✅ Token obtido: $TOKEN"
echo

# 2. Validar token
echo "2. Validando token..."
curl -s -X GET "$BASE_URL/validate-token" \
  -H "Authorization: Bearer $TOKEN" | jq .
echo

# 3. Obter dados financeiros
echo "3. Obtendo dados financeiros..."
curl -s -X GET "$BASE_URL/financas" \
  -H "Authorization: Bearer $TOKEN" | jq .
echo

# 4. Adicionar uma receita
echo "4. Adicionando receita..."
curl -s -X POST "$BASE_URL/receitas" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "descricao": "Salário Teste",
    "valor": 2500.00,
    "data": "2025-06-02"
  }' | jq .
echo

# 5. Adicionar uma despesa
echo "5. Adicionando despesa..."
curl -s -X POST "$BASE_URL/despesas" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "descricao": "Almoço Teste",
    "categoria": "alimentacao",
    "valor": 35.50,
    "data": "2025-06-02"
  }' | jq .
echo

# 6. Obter receitas
echo "6. Listando receitas..."
curl -s -X GET "$BASE_URL/receitas" \
  -H "Authorization: Bearer $TOKEN" | jq .
echo

# 7. Obter despesas
echo "7. Listando despesas..."
curl -s -X GET "$BASE_URL/despesas" \
  -H "Authorization: Bearer $TOKEN" | jq .
echo

# 8. Adicionar receita ao planejamento
echo "8. Adicionando receita ao planejamento..."
curl -s -X POST "$BASE_URL/planejamento/receitas" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "descricao": "Freelance Futuro",
    "valor": 800.00
  }' | jq .
echo

# 9. Obter planejamento
echo "9. Obtendo planejamento..."
curl -s -X GET "$BASE_URL/planejamento" \
  -H "Authorization: Bearer $TOKEN" | jq .
echo

# 10. Teste com dados completos (simular sincronização do celular)
echo "10. Sincronizando dados completos..."
curl -s -X POST "$BASE_URL/financas" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
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
        "data": "2025-06-02"
      }
    ],
    "despesas": [
      {
        "id": 1748886107034,
        "descricao": "Uber",
        "categoria": "transporte",
        "valor": 60,
        "data": "2025-06-02"
      },
      {
        "id": 1748886397199,
        "descricao": "Almoço",
        "categoria": "alimentacao",
        "valor": 30,
        "data": "2025-06-02"
      }
    ],
    "planejamento": {
      "receitas": [
        {
          "id": 1748884007296,
          "descricao": "Projeto X",
          "valor": 1200
        }
      ],
      "despesas": [
        {
          "id": 1748884316833,
          "descricao": "Curso Online",
          "categoria": "educacao",
          "valorTotal": 500,
          "valor": 50,
          "parcelada": true,
          "numParcelas": 10,
          "parcelaAtual": 1,
          "taxaJuros": 0
        }
      ]
    }
  }' | jq .
echo

# 11. Verificar dados finais
echo "11. Verificando dados finais..."
curl -s -X GET "$BASE_URL/financas" \
  -H "Authorization: Bearer $TOKEN" | jq .
echo

echo "=== TESTE CONCLUÍDO ==="
