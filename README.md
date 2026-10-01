# Jogja Center

## What is this?

Website

## Planned features

idk

## Project structure

```bash
.
├── apps
│   ├── backend                     # Laravel
│   │   ├── app
│   │   │   ├── Http/Controllers
│   │   │   ├── Models
│   │   │   └── Providers
│   │   ├── bootstrap
│   │   ├── config
│   │   ├── database
│   │   │   ├── factories
│   │   │   ├── migrations
│   │   │   └── seeders
│   │   ├── public
│   │   ├── resources
│   │   │   ├── css
│   │   │   ├── js
│   │   │   └── views
│   │   ├── routes
│   │   ├── storage
│   │   ├── tests
│   │   │   ├── Feature
│   │   │   └── Unit
│   │   ├── .env.example
│   │   ├── .env
│   │   ├── artisan
│   │   ├── composer.json
│   │   ├── compose.yaml            # Podman for setup postgre database
│   │   ├── package.json
│   │   ├── phpunit.xml
│   │   └── vite.config.js
│   │
│   └── frontend                    # Vue 3 + Vite (TypeScript)
│       ├── public
│       ├── src
│       │   ├── assets
│       │   └── components
│       ├── .env
│       ├── .env.example
│       ├── index.html
│       ├── package.json
│       ├── tsconfig.json
│       └── vite.config.ts
│
├── docs
│   ├── backend
│   │   └── 1-INSTALLATION.md
│   │
│   └── frontend
│       └── 1-INSTALLATION.md
│
├── packages                        # shared/internal packages
├── README.md
└── .gitignore
```

## How to maintain?

read docs
