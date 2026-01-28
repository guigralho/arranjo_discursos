# Estrutura do Codebase

## Diretórios Principais

```
arranjo_discursos_php/
├── app/
│   ├── Console/           # Comandos Artisan
│   ├── Exceptions/        # Exception handlers
│   ├── Exports/           # Classes de exportação (Excel/PDF)
│   │   ├── BusListExport.php
│   │   ├── BusListSheet.php
│   │   ├── ReceiveSpeakersExport.php
│   │   ├── ScheduleExport.php
│   │   └── SpeakerSpeechesExport.php
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Auth/      # Controllers de autenticação
│   │       ├── BusController.php
│   │       ├── DashboardController.php
│   │       ├── PassengerController.php
│   │       ├── ProfileController.php
│   │       ├── ScheduleController.php
│   │       ├── SpeakerController.php
│   │       ├── SpeakerSpeechController.php
│   │       └── SpeechController.php
│   ├── Models/
│   │   ├── Scopes/        # Global Scopes
│   │   │   └── CongregationScope.php
│   │   ├── Bus.php
│   │   ├── Passenger.php
│   │   ├── ReceiveSpeakers.php
│   │   ├── Schedule.php
│   │   ├── SendSpeakers.php
│   │   ├── Speaker.php
│   │   ├── SpeakerSpeech.php
│   │   ├── Speech.php
│   │   └── User.php
│   ├── Observers/         # Model observers
│   ├── Providers/         # Service providers
│   ├── Services/          # Business logic services
│   └── functions.php      # Funções helpers globais
│
├── config/                # Configurações Laravel
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Migrações do banco
│   └── seeders/           # Database seeders
│
├── resources/
│   └── js/
│       ├── Components/    # Componentes Vue reutilizáveis
│       ├── Layouts/       # Layouts de página
│       ├── Pages/         # Páginas Inertia
│       │   ├── Auth/
│       │   ├── Bus/
│       │   ├── Passenger/
│       │   ├── Profile/
│       │   ├── Schedule/
│       │   ├── Speaker/
│       │   ├── Speech/
│       │   ├── Dashboard.vue
│       │   └── Welcome.vue
│       ├── composables/   # Vue composables
│       ├── app.js         # Entry point
│       └── bootstrap.js   # Bootstrap config
│
├── routes/
│   ├── web.php            # Rotas web principais
│   ├── auth.php           # Rotas de autenticação
│   ├── api.php            # Rotas API
│   └── breadcrumbs.php    # Configuração de breadcrumbs
│
├── tests/                 # Testes PHPUnit
│
├── .eslintrc.js           # Configuração ESLint
├── .prettierrc.json       # Configuração Prettier
├── composer.json          # Dependências PHP
├── package.json           # Dependências JS
├── tailwind.config.js     # Configuração Tailwind
├── vite.config.js         # Configuração Vite
└── docker-compose.yml     # Docker/Sail config
```

## Relacionamentos entre Models

```
User
├── congregation_id (para multi-tenancy)
└── meeting_day

Speaker
├── congregation_id
├── speeches (belongsToMany Speech)
└── lastSpeechMade (hasOne SendSpeakers)

Speech
├── speakers (belongsToMany Speaker)
└── number (número do discurso)

Schedule (programação mensal)
├── congregation_id
├── month
├── toReceive (hasMany ReceiveSpeakers)
└── toSend (hasMany SendSpeakers)

SendSpeakers
├── schedule_id
├── speaker_id
└── date

ReceiveSpeakers
├── schedule_id
├── is_guest
└── date

Bus
└── passengers (hasMany Passenger)

Passenger
└── bus_id
```
