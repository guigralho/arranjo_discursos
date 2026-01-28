# Convenções e Estilo de Código

## PHP/Laravel

### Models
- Usar `HasFactory` e `SoftDeletes` traits quando apropriado
- Implementar `boot()` para registrar observers
- Implementar `booted()` para global scopes e creating hooks
- Formatar datas com timezone: `Carbon::parse($date, 'UTC')->setTimezone(config('app.timezone'))`
- Usar accessors para formatação de atributos (`getXxxAttribute`)
- Definir `$appends` para atributos computados

### Observers
- Localização: `app/Observers/`
- Usar `DeleteObserver` para soft deletes com registros relacionados

### Global Scopes
- Localização: `app/Models/Scopes/`
- `CongregationScope`: Filtra registros por `congregation_id` do usuário autenticado

### Controllers
- Localização: `app/Http/Controllers/`
- Usar Inertia para renderização: `Inertia::render('Page', $props)`
- Resource controllers para CRUD padrão

### Exports
- Localização: `app/Exports/`
- Usar Maatwebsite/Excel para CSV e Excel
- Usar DomPDF para PDFs

### Formato de Datas
- Display: `d/m/Y` (brasileiro)
- Com hora: `d/m/Y H:i:s`
- Mês/Ano: `m/Y`

## Vue.js/Frontend

### Estrutura de Pastas
```
resources/js/
├── Pages/           # Páginas Inertia (por recurso)
│   ├── Speaker/
│   ├── Speech/
│   ├── Schedule/
│   └── ...
├── Components/      # Componentes reutilizáveis
├── Layouts/         # Layouts de página
└── composables/     # Composables Vue
```

### ESLint
- Extensões: `eslint:recommended`, `plugin:vue/vue3-strongly-recommended`, `prettier`
- `vue/require-default-prop`: desabilitado

### Prettier
- Plugin: `prettier-plugin-tailwindcss` (ordena classes Tailwind)

## Rotas
- Usar named routes
- Resource routes para CRUD
- Agrupar rotas autenticadas com middleware `auth`
