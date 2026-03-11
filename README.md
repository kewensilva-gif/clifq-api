# CLIFQ API — Backend de Gestão de Empréstimos de Equipamentos

> API RESTful em Laravel para o sistema CLIFQ (Condomínio de Laboratórios Integrados de Física e Química), com gerenciamento de equipamentos, empréstimos e controle de acesso por papéis.

[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Laravel 11](https://img.shields.io/badge/Laravel-11.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Sail-2496ED?logo=docker&logoColor=white)](https://laravel.com/docs/sail)

---

## Sumário

- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias](#tecnologias)
- [Arquitetura](#arquitetura)
- [Endpoints da API](#endpoints-da-api)
- [Modelos de Dados](#modelos-de-dados)
- [Autenticação e Autorização](#autenticação-e-autorização)
- [Instalação e Execução](#instalação-e-execução)
- [Variáveis de Ambiente](#variáveis-de-ambiente)

---

## Sobre o Projeto

O **CLIFQ API** é o backend que alimenta o sistema de gestão de laboratórios. Ele permite:

- **Cadastro e autenticação** de usuários com tokens via Laravel Sanctum
- **CRUD de equipamentos** com upload de imagens
- **Gestão completa do ciclo de empréstimos:** solicitação → autorização/recusa → retirada → devolução
- **Controle de acesso por papéis** (Admin, Secretário, Técnico)
- **Listagem paginada e buscável** de usuários e equipamentos

O frontend correspondente está no projeto **cppgi-frontend-vue** (Nuxt 3).

---

## Tecnologias

| Categoria | Tecnologia |
|---|---|
| **Linguagem** | PHP 8.2+ |
| **Framework** | Laravel 11.x |
| **Autenticação** | Laravel Sanctum 4.x (tokens API) |
| **Scaffolding** | Laravel Breeze (API-only) |
| **Banco de Dados** | MySQL 8.0 |
| **Cache/Queue** | Redis |
| **Testes** | Pest 2.x |
| **Ambiente Dev** | Laravel Sail (Docker: PHP 8.3, MySQL, Redis) |
| **i18n** | laravel-lang/common |

---

## Arquitetura

O projeto segue uma arquitetura em camadas com padrões do Laravel:

```
app/
├── Http/
│   ├── Controllers/        # Controladores (Auth, User, Equipament, Loan)
│   ├── Requests/           # Form Requests (validação)
│   └── Middleware/          # Verificação de e-mail
├── Models/                  # Eloquent Models (User, Role, Equipament, LoansEquipament)
├── Services/                # Camada de serviço (lógica de negócio)
├── Policies/                # Políticas de autorização (role-based)
├── DTOs/                    # Data Transfer Objects para respostas da API
└── Enums/                   # PHP Enums (Role, LoanStatus, Equipament)
```

### Padrões Utilizados

| Padrão | Descrição |
|---|---|
| **Service Layer** | Lógica de negócio extraída dos controllers |
| **DTOs** | Formatação de dados para respostas (com campos calculados) |
| **Form Requests** | Validação de entrada em classes dedicadas |
| **Policies** | Autorização baseada em roles via Laravel Policies |
| **Backed Enums** | PHP 8.1 enums com labels e cores para status |

---

## Endpoints da API

### Autenticação

| Método | Rota | Auth | Descrição |
|---|---|---|---|
| `POST` | `/register` | Pública | Registrar usuário (retorna token Sanctum) |
| `POST` | `/login` | Pública | Login (retorna token Sanctum) |
| `POST` | `/logout` | Token | Revogar token atual |
| `POST` | `/forgot-password` | Pública | Enviar link de reset de senha |
| `POST` | `/reset-password` | Pública | Resetar senha |
| `GET` | `/verify-email/{id}/{hash}` | Assinada | Verificar e-mail |
| `POST` | `/email/verification-notification` | Token | Reenviar e-mail de verificação |

### Usuários

| Método | Rota | Auth | Descrição |
|---|---|---|---|
| `GET` | `/user/me` | Token | Dados do usuário autenticado |
| `GET` | `/users` | Token | Listar usuários (paginado, buscável) |
| `GET` | `/users/{user}` | Token | Detalhes de um usuário |

### Equipamentos

| Método | Rota | Auth | Descrição |
|---|---|---|---|
| `GET` | `/equipaments` | Token | Listar equipamentos (paginado, 8/página) |
| `POST` | `/equipaments` | Admin/Secretário | Criar equipamento (com upload de imagem) |
| `GET` | `/equipaments/{id}` | Token | Detalhes do equipamento |
| `PUT` | `/equipaments/{id}` | Admin/Secretário | Atualizar equipamento |
| `DELETE` | `/equipaments/{id}` | Admin | Remover equipamento |

### Empréstimos

| Método | Rota | Auth | Descrição |
|---|---|---|---|
| `GET` | `/loans` | Admin/Secretário | Listar todos os empréstimos (filtrável) |
| `GET` | `/loans/my_loans` | Token | Listar empréstimos do usuário logado |
| `POST` | `/loans` | Token | Solicitar empréstimo |
| `GET` | `/loans/{id}` | Token | Detalhes do empréstimo |
| `PUT` | `/loans/{id}` | Admin/Secretário | Atualizar empréstimo |
| `DELETE` | `/loans/{id}` | Admin | Remover empréstimo |
| `POST` | `/loans/{id}/authorization` | Admin/Secretário | Autorizar ou recusar empréstimo |
| `POST` | `/loans/{id}/withdrawal` | Admin/Secretário | Registrar retirada do equipamento |
| `POST` | `/loans/{id}/return` | Admin/Secretário | Registrar devolução do equipamento |

---

## Modelos de Dados

```
Role                        User
├── id                      ├── id, role_id (FK)
├── name                    ├── name, nickname, email, nif
                            ├── matriculation, phone, image
                            ├── password, activated
                            └── email_verified_at

Equipament                  LoansEquipament
├── id, name                ├── id
├── description             ├── id_requester (FK → User)
├── manufacturer_number     ├── id_secretary (FK → User)
├── asset_number            ├── id_equipament (FK → Equipament)
├── brand, model            ├── justification
├── image, loaned           ├── status (Pendente/Autorizado/Recusado)
                            ├── authorization_date
                            ├── withdrawal_date
                            └── return_date
```

### Roles

| ID | Nome | Permissões |
|---|---|---|
| 1 | **Admin** | Acesso completo (CRUD de tudo, excluir empréstimos) |
| 2 | **Secretário** | Criar/editar equipamentos, gerenciar empréstimos |
| 3 | **Técnico** | Solicitar empréstimos, visualizar seus próprios empréstimos |

### Ciclo de Vida do Empréstimo

```
Solicitação → Pendente → Autorizado/Recusado → Retirada → Devolução
   (Tech)      (Auto)    (Admin/Secretary)    (Admin/Sec)  (Admin/Sec)
```

---

## Autenticação e Autorização

- **Laravel Sanctum** — autenticação via tokens API (Bearer token)
- **Roles via banco de dados** — `roles` table com 3 papéis seedados
- **Laravel Policies** — controle de acesso a equipamentos e empréstimos
- **Verificação de e-mail** — fluxo completo de confirmação

---

## Instalação e Execução

### Com Laravel Sail (recomendado)

```bash
# Clone o repositório
git clone <url-do-repositorio>
cd cppgi-backend-laravel

# Copie o .env
cp .env.example .env

# Instale dependências com Sail
docker run --rm -v $(pwd):/opt -w /opt laravelsail/php83-composer:latest composer install

# Suba os containers (PHP + MySQL + Redis)
./vendor/bin/sail up -d

# Gere a chave da aplicação
./vendor/bin/sail artisan key:generate

# Execute as migrations e seeders
./vendor/bin/sail artisan migrate --seed

# Crie o link simbólico para storage
./vendor/bin/sail artisan storage:link
```

A API estará disponível em **http://localhost**.

### Criar o banco manualmente (se necessário)

```bash
docker exec -it mysql bash
mysql -u root -p
# senha: password (padrão do Sail)
CREATE DATABASE clifq;
GRANT ALL PRIVILEGES ON clifq.* TO 'sail'@'%';
FLUSH PRIVILEGES;
```

### Resolver erro de permissão no storage

```bash
sudo chmod o+w ./storage/ -R
sudo chown www-data:www-data -R ./storage
```

---

## Variáveis de Ambiente

| Variável | Descrição | Valor padrão |
|---|---|---|
| `DB_CONNECTION` | Driver do banco | `mysql` |
| `DB_HOST` | Host do MySQL | `mysql` |
| `DB_DATABASE` | Nome do banco | `clifq` |
| `DB_USERNAME` | Usuário do banco | `sail` |
| `DB_PASSWORD` | Senha do banco | `password` |
| `FRONTEND_URL` | URL do frontend (CORS) | `http://localhost:3000` |

---

## Testes

```bash
./vendor/bin/sail artisan test
# ou
./vendor/bin/pest
```

---

## Estrutura de Pastas

```
cppgi-backend-laravel/
├── app/
│   ├── DTOs/                # EquipamentDTO, LoanEquipamentDTO
│   ├── Enums/               # RoleEnum, EquipamentEnum, LoanStatusEnum
│   ├── Http/Controllers/    # Auth, User, Equipament, Loan controllers
│   ├── Http/Requests/       # Form request validations
│   ├── Models/              # User, Role, Equipament, LoansEquipament
│   ├── Policies/            # Equipament, Loan policies
│   └── Services/            # Business logic services
├── database/
│   ├── factories/           # Test factories
│   ├── migrations/          # Schema migrations
│   └── seeders/             # Role, User, Equipament seeders
├── routes/
│   ├── api.php              # API routes
│   └── auth.php             # Auth routes (Breeze)
├── docker-compose.yml       # Sail: PHP 8.3, MySQL 8.0, Redis
└── composer.json
```

---

<p align="center">
  Feito com Laravel 11 + Sanctum + MySQL
</p>
