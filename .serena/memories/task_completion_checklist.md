# Checklist de Conclusão de Tarefa

## Antes de Finalizar uma Tarefa

### 1. Formatação de Código
- [ ] Executar `./vendor/bin/pint --dirty` para PHP
- [ ] Verificar ESLint para arquivos Vue/JS modificados

### 2. Testes
- [ ] Escrever testes para novas funcionalidades
- [ ] Executar `sail artisan test` para garantir que não quebrou nada
- [ ] Verificar se todos os testes passam

### 3. Multi-Tenancy
- [ ] Verificar se novos models usam `CongregationScope` quando necessário
- [ ] Garantir que `congregation_id` é atribuído em `booted()` ao criar registros

### 4. Frontend
- [ ] Se alterou Vue/JS, executar `npm run build` ou verificar com `npm run dev`
- [ ] Verificar responsividade (Tailwind mobile-first)

### 5. Banco de Dados
- [ ] Se criou migration, testar com `sail artisan migrate:fresh`
- [ ] Verificar se seeders foram atualizados se necessário

### 6. Documentação
- [ ] Atualizar CLAUDE.md se houver novas convenções
- [ ] Adicionar PHPDoc para métodos complexos

## Padrões a Seguir

### Models
- Usar traits: `HasFactory`, `SoftDeletes`
- Implementar `boot()` para observers
- Implementar `booted()` para scopes e hooks
- Accessors para formatação de datas

### Controllers
- Usar Inertia::render() para views
- Resource controllers para CRUD
- Form Requests para validação

### Vue Components
- Colocar em `resources/js/Pages/{Recurso}/`
- Usar composables para lógica compartilhada
- Seguir convenções existentes de componentes
