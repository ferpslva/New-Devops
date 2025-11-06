Este documento explica o fluxo de trabalho do projeto.

## 🎯 Visão Geral

O projeto é composto por uma aplicação em PHP 8 e MYSQL 8.0 se tratando de categorias e produtos, onde cada produto precisa ter uma categoria (ambos com CRUD completo).

O código se encontra no GitHub que é utilizado para testes e deploy.

O projeto usa um fluxo de CI/CD automatizado com duas branches principais:
- **develop**: Branch de desenvolvimento
- **master**: Branch de produção

## 🔄 Fluxo de Trabalho

### 1. Desenvolvimento de Features

```bash
# Criar nova branch a partir de develop
git clone https://github.com/ferpslva/New-Devops.git
Fazer um pull na branch develop
Criar uma nova branch
Utilizar a IDE VSCode para alterações
Selecionar os itens alterados e fazer um commit

### 2. Pull Request para Develop

1. **Abrir PR** no GitHub:
   - Importante que a PR seja da branch criada para develop
2. **CI executa automaticamente**:
3. **Aguardar resultado**:
   - ✅ Verde = Pode fazer merge
   - ❌ Vermelho = Corrigir problemas e push novamente

### 3. Merge em Develop

# Após aprovação e CI passar
# Fazer merge do PR no GitHub
# ⚠️ NENHUM deploy acontece aqui!
```

### 4. Deploy em Produção

Quando estiver pronto para produção:

```bash
# Criar PR de develop para master
# No GitHub: develop → master
```

1. **CI valida novamente**
2. **Aguardar aprovação para autorizar a merge**
3. **CD deploy automático** 🚀
   - Sincroniza código para VPS
   - Backup do banco de dados
   - Rebuild dos containers
   - Health check da aplicação

---

## 🛡️ Proteções

### Branch `develop`
- ✅ CI obrigatório antes de merge
- ✅ Testes devem passar
- ✅ Build Docker deve compilar

### Branch `master`
- ✅ CI obrigatório antes de merge
- ✅ Todos os checks devem passar
- 🚀 Deploy automático após merge

---

## 🧪 CI - Integração Contínua

### O que o CI valida:

1. **Testes Unitários e de Integração**
   - Executa PHPUnit
   - Testa CRUD completo
   - Valida integração com MySQL

2. **Build Docker**
   - Valida que o Dockerfile compila
   - Garante que a imagem pode ser construída

3. **Schema SQL**
   - Valida estrutura do banco
   - Cria tabelas de teste
   - Garante compatibilidade

### Quando o CI executa:
- ✅ Todo PR para `develop`
- ✅ Todo PR para `master`

---

## 🚀 CD - Deploy Contínuo

### O que o CD faz:

1. **Sincronização de Código**
   - rsync completo para `/opt/sistema_av_iii/`
   - Exclui: .git, node_modules, vendor, backups

2. **Backup do Banco**
   - Mysqldump automático
   - Mantém últimos 7 backups

3. **Deploy dos Containers**
   - Para containers antigos
   - Rebuild com novo código
   - Restart automático

4. **Health Check**
   - Verifica se aplicação está respondendo
   - 10 tentativas com 5s de intervalo
   - Falha se não responder

### Quando o CD executa:
- ✅ Apenas em push/merge para `master`
- ❌ Nunca em develop ou outras branches

---

## 📊 Status dos Workflows

### CI
Os PRs mostrarão um dos seguintes status:
- 🟢 **Success**: Todos os checks passaram → Pode fazer merge
- 🔴 **Failed**: Algum check falhou → Corrigir antes de merge
- 🟡 **Running**: CI em execução → Aguardar

### CD
Após merge em master:
- 🟢 **Success**: Deploy completado com sucesso
- 🔴 **Failed**: Deploy falhou

---

## 🔍 Troubleshooting

### Em caso de falhas no CI/CD:
1. Verificar logs no GitHub Actions

## 🌐 URLs

- **Produção**: http://191.252.204.148:8080

---

## 📝 Boas Práticas

1. ✅ Sempre criar feature branches
2. ✅ Escrever mensagens de commit descritivas
3. ✅ Não fazer push direto em develop ou master
4. ✅ Usar PRs mesmo para pequenas mudanças

---

# Monitoramento utilizando Grafana e Dozzle

## 🎯 Visão Geral

1. **Grafana Cloud** - Dashboard profissional com métricas e logs históricos
2. **Dozzle** - Interface web para logs em tempo real


### 2. Configurar Grafana Cloud (Métricas + Logs)

#### Passo 1: Criar conta gratuita
#### Passo 2: Obter credenciais do Prometheus
#### Passo 3: Instalar Grafana Alloy no VPS

## 🖥️ Como Acessar

### 📊 Grafana Cloud (Dashboard Principal)

**URL**: https://[seu-stack].grafana.net
**O que você vê**:
- Métricas do servidor (CPU, RAM, Disco, Rede)
- Logs dos containers (Nginx, PHP, MySQL)

#### Navegando no Grafana:

**1. Ver Métricas:**
- Queries úteis:
  ```
  up                                 # Servidor online (1 = sim, 0 = não)
  node_cpu_seconds_total             # CPU por core
  node_memory_MemAvailable_bytes     # RAM disponível
  ```

**2. Ver Logs:**
- Queries úteis:
  ```
  {container="mvc_app_web"}              # Logs do Nginx
  {container="mvc_app_php"}              # Logs do PHP
  {container="mvc_app_db"}               # Logs do MySQL
  {container=~".+"} |= "error"           # Todos os erros
  ```

**Dozzle**
Dozzle é deployado automaticamente pelo CD
Sempre que você fizer push em `master`, o workflow de CD:
1. Faz deploy da aplicação
2. **Sobe o Dozzle automaticamente**

**Acesso**: http://191.252.204.148:9999


### 📝 Dozzle (Logs em Tempo Real)

**URL**: http://191.252.204.148:9999
**O que você vê**:
- Lista de todos os containers rodando
- Logs em tempo real (live streaming)

## 📊 Métricas Importantes

### CPU Usage

**Limites:**
- **Normal**: < 70%
- **Alerta**: > 80%
- **Crítico**: > 90%

### Memória

**Limites:**
- **Normal**: > 200MB disponível
- **Alerta**: < 150MB disponível
- **Crítico**: < 100MB disponível

---

## Possibilidade de Configurar Alertas e Dashboards personalizados

## Custos

- **Dozzle**: Grátis
- **Grafana Cloud**: Grátis
- **Grafana Alloy**: Grátis
- **Logs Nativos**: Grátis

**Total: R$ 0,00**

---

Última atualização: 05/11/2025
