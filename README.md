
# 📦 Rede de Estoque — Sistema de Gerenciamento de Produtos

Este projeto é um sistema completo de controle de estoque construído com **Laravel** (API REST com autenticação JWT) e **React + Inertia.js** no frontend. Ele utiliza **Docker** para simplificar a configuração e execução do ambiente de desenvolvimento.

---

## 🚀 Funcionalidades principais

- Cadastro e login de usuários com autenticação via JWT.
- Controle de acesso baseado em permissões (Administrador, Operador, Usuário comum).
- Cadastro, listagem e edição de produtos.
- Interface moderna e responsiva.
- API RESTful versionada em `/api/v1`.

---

## ⚙️ Requisitos

Para rodar este projeto, você precisa ter os seguintes softwares instalados:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Node.js](https://nodejs.org/) (versão 16 ou superior)
- [npm](https://www.npmjs.com/)

---

## 🛠️ Passo a passo para rodar o sistema

### 1. Clone o repositório

Clone o repositório do projeto e navegue até o diretório do backend:

```bash
git clone https://github.com/seu-usuario/rede-de-estoque.git
cd rede-de-estoque/backend
```

### 2. Configure e inicie os containers com Docker

No diretório `backend`, execute o comando abaixo para iniciar os containers Docker:

```bash
docker-compose up -d
```

Isso irá configurar o ambiente e iniciar o container do backend Laravel, incluindo a base de dados.

### 3. Instale as dependências do frontend

Ainda no diretório `backend` e instale as dependências necessárias:

```bash
npm install
```

### 4. Inicie o servidor de desenvolvimento do frontend

Após instalar as dependências, inicie o servidor de desenvolvimento:

```bash
npm run dev
```

### 5. Acesse o sistema

Após os passos acima, o sistema estará disponível no navegador em:

```
http://localhost:8000
```

> **Nota:** Certifique-se de que o Docker está em execução e que as portas necessárias estão livres no seu sistema.

## Dados Iniciais

#### 👤 Usuários Criados

| Nome     | E-mail               | Senha     | Permissão     |
|----------|----------------------|-----------|----------------|
| Admin    | admin@example.com    | password  | Administrador  |
| Operador | operador@example.com | password  | Operador       |

> ✅ Todos os novos usuários cadastrados recebem, por padrão, a permissão de **Usuário Comum**.  
> 🔐 Os tokens JWT devem ser obtidos via `/api/v1/login` utilizando e-mail e senha.

#### 🧪 Dados de Teste

O banco de dados é populado automaticamente durante o build da aplicação.

- ✅ Um seeder insere **150 registros** de dados fictícios (ex: produtos, categorias, etc.) para facilitar testes de listagem, filtros, paginação e funcionalidades gerais.
- ⚙️ Isso ocorre automaticamente quando o ambiente é iniciado, sem necessidade de rodar comandos manuais.

> Caso precise executar novamente:
>
> ```bash
> php artisan db:seed
> ```


## 📦 Estrutura de Permissões

* **Administrador (role\_id = 1)** → acesso total a todos os recursos.
* **Operador (role\_id = 2)** → acesso operacional aos produtos, sem controle de usuários.
* **Usuário comum (role\_id = 3)** → acesso limitado apenas à visualização dos dados.

---

## 📚 Principais dependências

### Backend (Laravel)

* Laravel 10+
* JWT Auth (`tymon/jwt-auth`)
* Docker + MySQL
* Inertia.js

### Frontend

* React 18+
* Inertia.js
* TailwindCSS
* Styled-components
* MUI DataGrid (usado inicialmente, mas substituído por Tailwind no visual final)

---

## 🧪 Endpoints principais
| **Método** | **Endpoint**          | **Descrição**                     | **Autenticação** |
|:----------:|:---------------------:|:---------------------------------:|:----------------:|
| **POST**   | `/api/v1/register`    | Registro de novo usuário          |        ❌         |
| **POST**   | `/api/v1/login`       | Login e geração de token JWT      |        ❌         |
| **GET**    | `/api/v1/produtos`    | Lista todos os produtos           |        ✅         |
| **POST**   | `/api/v1/produtos`    | Cria um novo produto (ACL aplicada)|       ✅         |
| **PUT**    | `/api/v1/produtos/:id`| Edita um produto                  |        ✅         |
| **DELETE** | `/api/v1/produtos/:id`| Remove um produto                 |        ✅         |

> **Nota:** Todas as rotas protegidas requerem autenticação via token JWT no header:
> `Authorization: Bearer <seu_token_aqui>`

---

Com fé, Docker e devtools, tá rodando. 🚀
