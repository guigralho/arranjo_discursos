# Comandos Sugeridos

## Ambiente de Desenvolvimento

### Iniciar o Ambiente
```bash
# Usar alias do Sail
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'

# Subir containers
sail up -d

# Ou diretamente
./vendor/bin/sail up -d
```

### Parar o Ambiente
```bash
sail down
```

## Desenvolvimento

### Frontend (Vite)
```bash
# Desenvolvimento com hot reload
sail npm run dev

# Build para produção
sail npm run build
```

### Migrações
```bash
# Executar migrações
sail artisan migrate

# Rollback
sail artisan migrate:rollback

# Fresh (drop all + migrate)
sail artisan migrate:fresh

# Com seeders
sail artisan migrate:fresh --seed
```

### Artisan
```bash
# Criar controller
sail artisan make:controller NomeController

# Criar model com migration, factory e seeder
sail artisan make:model Nome -mfs

# Criar migration
sail artisan make:migration create_nome_table

# Limpar caches
sail artisan optimize:clear
```

## Qualidade de Código

### PHP (Pint)
```bash
# Formatar todos os arquivos
sail ./vendor/bin/pint

# Formatar apenas arquivos modificados
sail ./vendor/bin/pint --dirty

# Verificar sem corrigir
sail ./vendor/bin/pint --test
```

### JavaScript/Vue (ESLint)
```bash
# Verificar
sail npx eslint resources/js

# Corrigir automaticamente
sail npx eslint resources/js --fix
```

### Prettier
```bash
# Formatar
sail npx prettier --write "resources/js/**/*.{js,vue}"
```

## Testes

### PHPUnit
```bash
# Executar todos os testes
sail artisan test

# Executar teste específico
sail artisan test --filter=NomeDoTeste

# Com coverage
sail artisan test --coverage
```

## Outros Comandos Úteis

```bash
# Storage link
sail artisan storage:link

# Tinker (REPL)
sail artisan tinker

# Listar rotas
sail artisan route:list

# Gerar autoload otimizado
sail composer dump-autoload -o
```
