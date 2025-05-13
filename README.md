# 🏀 Análise de Jogadores de Basquete

Este é um sistema web construído com **Laravel** para auxiliar treinadores e jogadores na **análise de habilidades individuais no basquete**. O sistema permite o cadastro de atletas, armazenamento de imagens em base64, atualização via CPF, e exibição de gráficos comparativos de desempenho.

---

## 🚀 Tecnologias Utilizadas

- **Laravel 11** (PHP Framework)
- **PHP 8.2+**
- **Blade** (Template Engine do Laravel)
- **MySQL** ou **PostgreSQL**
- **Chart.js** (Gráficos de barra comparativos)
- **HTML5 + CSS3 + JavaScript**
- **Bootstrap** (ou outro framework CSS, se usado)
- **Arquitetura MVC** com camadas de:
  - **Service**
  - **Repository**
- **Manipulação de Imagens em Base64**
- **Validação via CPF**

---

## 🧱 Funcionalidades Principais

- Cadastro de atletas com CPF único - A desenvolver
- Upload de imagens em base64
- Atualização e exclusão de registros com base no CPF - A desenvolver
- Comparação de habilidades entre análises anteriores
- Visualização de gráficos individuais
- Interface adaptada para treinadores e jogadores

---

## ⚙️ Como Rodar o Projeto Localmente

### 1. Clone o repositório
```bash
git clone https://github.com/sandoelio/analiseJogadores.git
cd analiseJogadores
```

### 2. Instale as dependências do PHP

```bash
composer install
```
### 3. Crie o arquivo `.env` e configure as variáveis de ambiente
```bash
cp .env.example .env
```
### 4. Gere a chave de aplicação
```bash
php artisan key:generate
```
### 5. Execute as migrações
```bash
php artisan migrate
```
### 6. Inicie o servidor local
```bash
php artisan serve
```
### 7. Acesse o sistema
Abra o navegador e acesse `http://localhost:8000` ou a URL fornecida pelo comando `php artisan serve`.
```
