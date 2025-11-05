# 📋 Guia de Workflow CI/CD

Este documento explica o fluxo de trabalho de desenvolvimento e deploy do projeto.

---

## 🎯 Visão Geral

O projeto usa um fluxo de CI/CD automatizado com duas branches principais:
- **develop**: Branch de desenvolvimento (sem deploy automático)
- **master**: Branch de produção (deploy automático)

---

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
   - ✅ Instala dependências PHP
   - ✅ Configura banco de dados MySQL
   - ✅ Executa testes unitários e de integração
   - ✅ Valida build do Docker
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

1. **CI valida novamente** (segurança dupla)
2. **Aguardar aprovação** de reviewer
3. **Fazer merge** na branch master
4. **CD deploy automático** 🚀
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
- ✅ Recomendado: Requerer 1 aprovação
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
   - Não faz push (apenas validação)

3. **Schema SQL**
   - Valida estrutura do banco
   - Cria tabelas de teste
   - Garante compatibilidade

### Quando o CI executa:
- ✅ Todo PR para `develop`
- ✅ Todo PR para `master`
- ✅ Manualmente via workflow_dispatch

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
- ❌ NUNCA em develop ou outras branches

---

## 📊 Status dos Workflows

### CI Badge
Os PRs mostrarão um dos seguintes status:
- 🟢 **Success**: Todos os checks passaram → Pode fazer merge
- 🔴 **Failed**: Algum check falhou → Corrigir antes de merge
- 🟡 **Running**: CI em execução → Aguardar

### CD Badge
Após merge em master:
- 🟢 **Success**: Deploy completado com sucesso
- 🔴 **Failed**: Deploy falhou → Verificar logs

---

## 🔍 Troubleshooting

### CI falhou nos testes
### CI falhou no build Docker
### CD falhou no deploy
1. Verificar logs no GitHub Actions

## 🌐 URLs

- **Produção**: http://191.252.204.148:8080
- **GitHub Actions**: [Ver workflows](../../actions)

---

## 📝 Boas Práticas

1. ✅ Sempre criar feature branches
2. ✅ Escrever mensagens de commit descritivas
3. ✅ Aguardar CI passar antes de merge
4. ✅ Testar localmente antes de push
5. ✅ Não fazer push direto em develop ou master
6. ✅ Usar PRs mesmo para pequenas mudanças

---

## 🆘 Suporte

Em caso de dúvidas:
1. Verificar este documento
2. Ver logs do GitHub Actions
3. Consultar o time de DevOps
