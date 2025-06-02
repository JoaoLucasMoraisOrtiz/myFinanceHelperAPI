# Deploy e Configuração da API de Finanças

## Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Banco de dados (SQLite, MySQL, PostgreSQL)
- Servidor web (Apache, Nginx) ou usar `php artisan serve`

## Instalação Local

### 1. Clonar e configurar o projeto
```bash
cd /caminho/para/o/projeto
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configurar banco de dados
Edite o arquivo `.env`:

**Para SQLite (recomendado para desenvolvimento):**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/caminho/absoluto/para/database/database.sqlite
```

**Para MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=financas_api
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 3. Executar migrações e seeders
```bash
php artisan migrate:fresh --seed
```

### 4. Iniciar servidor de desenvolvimento
```bash
php artisan serve --host=0.0.0.0 --port=8080
```

A API estará disponível em: `http://localhost:8080/api/`

## Deploy em Produção

### 1. Configurações de Produção

**Arquivo .env para produção:**
```env
APP_NAME="API Financas"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=financas_api
DB_USERNAME=usuario_producao
DB_PASSWORD=senha_segura_producao

LOG_CHANNEL=daily
LOG_LEVEL=warning
```

### 2. Otimizações para Produção
```bash
# Otimizar autoload
composer install --optimize-autoloader --no-dev

# Cache de configuração
php artisan config:cache

# Cache de rotas
php artisan route:cache

# Cache de views
php artisan view:cache

# Otimizar aplicação
php artisan optimize
```

### 3. Configuração do Servidor Web

**Apache (.htaccess já incluído no Laravel)**
```apache
<VirtualHost *:80>
    ServerName seu-dominio.com
    DocumentRoot /var/www/financas-api/public
    
    <Directory /var/www/financas-api/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Nginx:**
```nginx
server {
    listen 80;
    server_name seu-dominio.com;
    root /var/www/financas-api/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Segurança em Produção

### 1. HTTPS
```bash
# Instalar SSL com Certbot (Let's Encrypt)
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d seu-dominio.com
```

### 2. Firewall
```bash
# UFW (Ubuntu)
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

### 3. Permissões de Arquivos
```bash
# Definir permissões corretas
sudo chown -R www-data:www-data /var/www/financas-api
sudo chmod -R 755 /var/www/financas-api
sudo chmod -R 775 /var/www/financas-api/storage
sudo chmod -R 775 /var/www/financas-api/bootstrap/cache
```

### 4. Backup Automático
```bash
#!/bin/bash
# backup_daily.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/financas-api"
PROJECT_DIR="/var/www/financas-api"

# Criar diretório de backup
mkdir -p $BACKUP_DIR

# Backup do banco de dados
if [ "$DB_CONNECTION" = "mysql" ]; then
    mysqldump -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE > $BACKUP_DIR/database_$DATE.sql
else
    cp $PROJECT_DIR/database/database.sqlite $BACKUP_DIR/database_$DATE.sqlite
fi

# Backup dos arquivos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz -C $PROJECT_DIR .

# Manter apenas últimos 7 backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.sqlite" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup concluído: $DATE"
```

## Monitoramento

### 1. Logs
```bash
# Ver logs em tempo real
tail -f storage/logs/laravel.log

# Ver logs de acesso (Apache)
tail -f /var/log/apache2/access.log

# Ver logs de erro (Apache)
tail -f /var/log/apache2/error.log
```

### 2. Verificação de Saúde
```bash
# Endpoint de saúde do Laravel
curl http://seu-dominio.com/up

# Teste básico da API
curl -X POST http://seu-dominio.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@financas.com","password":"123456"}'
```

## Configuração para Múltiplos Ambientes

### Desenvolvimento
```env
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite
```

### Teste
```env
APP_ENV=testing
APP_DEBUG=true
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### Produção
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
```

## Scripts Úteis

### Script de Deploy
```bash
#!/bin/bash
# deploy.sh

echo "Iniciando deploy..."

# Atualizar código
git pull origin main

# Instalar/atualizar dependências
composer install --optimize-autoloader --no-dev

# Executar migrações
php artisan migrate --force

# Limpar e recriar caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reiniciar serviços
sudo systemctl reload apache2

echo "Deploy concluído!"
```

### Script de Monitoramento
```bash
#!/bin/bash
# monitor.sh

API_URL="http://localhost:8080/api"

# Testar login
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -X POST "$API_URL/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@financas.com","password":"123456"}')

if [ "$RESPONSE" -eq 200 ]; then
    echo "✅ API está funcionando"
else
    echo "❌ API com problema (HTTP $RESPONSE)"
    # Adicionar notificação aqui
fi
```

## Troubleshooting

### Problemas Comuns

1. **Erro 500 - Internal Server Error**
   - Verificar logs: `tail storage/logs/laravel.log`
   - Verificar permissões dos diretórios
   - Verificar configuração do .env

2. **Erro de CORS**
   - Verificar se o CorsMiddleware está ativo
   - Verificar headers permitidos

3. **Erro de Banco de Dados**
   - Verificar conectividade: `php artisan tinker` → `DB::connection()->getPdo()`
   - Verificar credenciais no .env

4. **Token inválido**
   - Verificar se o header Authorization está correto
   - Verificar se o usuário existe no banco

### Comandos de Diagnóstico
```bash
# Verificar configuração
php artisan config:show

# Verificar rotas
php artisan route:list

# Verificar banco de dados
php artisan migrate:status

# Limpar tudo
php artisan optimize:clear
```
