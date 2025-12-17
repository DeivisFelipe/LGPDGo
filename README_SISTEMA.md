# LGPDGo

Sistema Laravel com Inertia.js, Vue.js, Vuetify e autenticação Sanctum, incluindo sistema de permissões e multi-tenancy.

## 🚀 Características

-   **Frontend**: Inertia.js + Vue.js 3 + Vuetify 3
-   **Autenticação**: Laravel Sanctum
-   **Sistema de Permissões**: Permissões individuais e grupos de permissões
-   **Multi-tenancy**: Isolamento de dados por empresa
-   **Superusuário**: Usuário dev com acesso total ao sistema

## 📋 Pré-requisitos

-   PHP >= 8.2
-   Composer
-   Node.js >= 18.x
-   MySQL ou MariaDB
-   Extensão PHP PDO MySQL

## 🔧 Instalação

### 1. Configurar Banco de Dados

Crie o banco de dados MySQL:

```bash
mysql -u root -p
CREATE DATABASE lgpdgo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

### 2. Instalar Dependências

```bash
# Dependências PHP (já instaladas)
composer install

# Dependências Node.js (já instaladas)
npm install --legacy-peer-deps
```

### 3. Executar Migrations e Seeders

```bash
php artisan migrate:fresh --seed
```

### 4. Compilar Assets

```bash
npm run dev
```

### 5. Iniciar o Servidor

Em outro terminal:

```bash
php artisan serve
```

Acesse: http://localhost:8000

## 👤 Credenciais do Superusuário

-   **Email**: dev@lgpdgo.com
-   **Senha**: password

## 📦 Estrutura do Banco de Dados

### Tabelas Principais

-   **companies**: Empresas cadastradas no sistema
-   **users**: Usuários vinculados a empresas
-   **permissions**: Permissões individuais
-   **permission_groups**: Grupos de permissões
-   **permission_group_permission**: Relacionamento muitos-para-muitos entre grupos e permissões
-   **user_permissions**: Permissões diretas dos usuários
-   **user_permission_groups**: Grupos de permissões dos usuários

## 🔐 Sistema de Permissões

### Middleware Disponíveis

-   `permission:slug`: Verifica se o usuário tem uma permissão específica
-   `super_user`: Restringe acesso apenas a superusuários

### Exemplo de Uso em Rotas

```php
Route::middleware(['auth', 'permission:manage-users'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});

Route::middleware(['auth', 'super_user'])->group(function () {
    Route::resource('companies', CompanyController::class);
});
```

### Verificar Permissões no Código

```php
// Verificar permissão individual
if ($user->hasPermission('manage-users')) {
    // ...
}

// Verificar qualquer permissão de uma lista
if ($user->hasAnyPermission(['manage-users', 'view-users'])) {
    // ...
}

// Verificar todas as permissões
if ($user->hasAllPermissions(['manage-users', 'manage-companies'])) {
    // ...
}

// Verificar se é superusuário
if ($user->isSuperUser()) {
    // ...
}
```

## 🏢 Multi-tenancy

### Trait BelongsToCompany

Adicione o trait aos models que devem ser isolados por empresa:

```php
use App\Traits\BelongsToCompany;

class MyModel extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'name', ...];
}
```

O trait automaticamente:

-   Adiciona um scope global filtrando por `company_id` do usuário autenticado
-   Define `company_id` automaticamente ao criar registros
-   Superusuários podem ver dados de todas as empresas

### Middleware TenantScope

O middleware `TenantScope` está ativo em todas as rotas web e garante:

-   Usuários devem estar associados a uma empresa
-   Superusuários têm acesso a todas as empresas

## 📊 Permissões Padrão

O sistema vem com as seguintes permissões pré-cadastradas:

-   `manage-companies`: Gerenciar empresas
-   `view-companies`: Visualizar empresas
-   `manage-users`: Gerenciar usuários
-   `view-users`: Visualizar usuários
-   `manage-permissions`: Gerenciar permissões
-   `view-permissions`: Visualizar permissões
-   `manage-permission-groups`: Gerenciar grupos de permissões
-   `view-permission-groups`: Visualizar grupos de permissões

## 📦 Grupos de Permissões Padrão

-   **Administrador**: Todas as permissões exceto gerenciar empresas
-   **Gerente**: Gerenciar e visualizar usuários
-   **Usuário Padrão**: Apenas visualizar usuários

## 🎨 Vuetify

O Vuetify está configurado com tema dark por padrão. Para usar componentes:

```vue
<template>
    <v-app>
        <v-btn color="primary">Botão</v-btn>
        <v-card>
            <v-card-title>Título</v-card-title>
            <v-card-text>Conteúdo</v-card-text>
        </v-card>
    </v-app>
</template>
```

## 🛠️ Comandos Úteis

```bash
# Executar migrations
php artisan migrate

# Limpar e recriar banco de dados com seeders
php artisan migrate:fresh --seed

# Compilar assets para produção
npm run build

# Compilar assets em modo de desenvolvimento
npm run dev

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📝 Próximos Passos

1. Criar controllers para gerenciar empresas, usuários, permissões e grupos
2. Criar páginas Vue.js com Vuetify para o frontend
3. Implementar validações e regras de negócio
4. Adicionar testes automatizados
5. Configurar CI/CD

## 📄 Licença

Este projeto está sob a licença MIT.
