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

## How to contribute?

### Branch naming

using this format, `github username/(feature/fix/etc)/name`

if want to merge, pull request to dev first, main branch is only touchable for the code owner (thehaidarbahzi)

### Commit message

please use a consise and easy to understand sentence, for example:

```
adding hero section on landing page, features consist of:

1. CTA button
2. image
3. etc.
```

## How to maintain?

its not developed yet bruh.
