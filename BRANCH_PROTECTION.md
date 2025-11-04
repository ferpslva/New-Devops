# 🛡️ Configuração de Proteção de Branches

Este guia explica como configurar as proteções de branch no GitHub para garantir que o CI/CD funcione corretamente.

---

## 📋 Pré-requisitos

- Permissão de administrador no repositório
- Branches `develop` e `master` já criadas

---

## ⚙️ Configurar Proteções

### 1. Acessar Configurações

1. Vá até o repositório no GitHub
2. Clique em **Settings** (Configurações)
3. No menu lateral, clique em **Branches**
4. Clique em **Add rule** (Adicionar regra)

---

### 2. Proteger Branch `develop`

Configure as seguintes opções:

#### Branch name pattern
```
develop
```

#### Regras a ativar:

✅ **Require a pull request before merging**
- Marca esta opção
- Deixa "Require approvals" desmarcado (opcional para develop)

✅ **Require status checks to pass before merging**
- Marca esta opção
- Clique em **Add** e procure por: `Testes e Build Docker`
- Selecione o check do CI

✅ **Require branches to be up to date before merging**
- Marca esta opção (recomendado)

❌ **Require conversation resolution before merging**
- Opcional (recomendado para equipes maiores)

❌ **Require signed commits**
- Opcional (segurança extra)

✅ **Do not allow bypassing the above settings**
- Marca esta opção

#### Salvar
Clique em **Create** no final da página

---

### 3. Proteger Branch `master`

Repita o processo acima, mas com as seguintes diferenças:

#### Branch name pattern
```
master
```

#### Regras a ativar:

✅ **Require a pull request before merging**
- Marca esta opção
- **IMPORTANTE**: Ative "Require approvals" e defina **1 approval**

✅ **Require status checks to pass before merging**
- Marca esta opção
- Adicione: `Testes e Build Docker`

✅ **Require branches to be up to date before merging**
- Marca esta opção

✅ **Require conversation resolution before merging**
- Recomendado para master

✅ **Do not allow bypassing the above settings**
- Marca esta opção

✅ **Require deployments to succeed before merging**
- Opcional (se quiser adicionar mais segurança)

#### Salvar
Clique em **Create** no final da página

---

## 🎯 Resumo das Proteções

| Regra | develop | master |
|-------|---------|--------|
| Require PR | ✅ | ✅ |
| Require approval | ❌ | ✅ (1) |
| Require CI pass | ✅ | ✅ |
| Up to date | ✅ | ✅ |
| Block force push | ✅ | ✅ |
| No bypass | ✅ | ✅ |

---

## ✅ Testar Configuração

### Teste 1: CI em PR para develop

```bash
# Criar feature branch
git checkout -b feature/test
echo "test" > test.txt
git add .
git commit -m "test: CI validation"
git push origin feature/test

# Abrir PR para develop
# CI deve executar automaticamente
# Aguardar badge verde/vermelho
```

### Teste 2: Tentar merge sem CI passar

1. Abra um PR para develop
2. Tente fazer merge antes do CI terminar
3. GitHub deve bloquear com mensagem: "Required status checks must pass"

### Teste 3: PR para master requer aprovação

1. Abra PR de develop → master
2. Tente fazer merge
3. GitHub deve bloquear com: "Requires 1 approving review"

---

## 🚨 Troubleshooting

### "Status check not found"

Se ao configurar as proteções você não encontrar o check `Testes e Build Docker`:

1. Primeiro execute o CI pelo menos uma vez (abra um PR teste)
2. Depois volte em Settings → Branches → Edit rule
3. O check agora deve aparecer na lista

### "Cannot push to protected branch"

Se você tentar fazer push direto:
```bash
git push origin develop
# Erro: protected branch
```

✅ **Solução**: Sempre use PRs
```bash
git checkout -b feature/minha-feature
git push origin feature/minha-feature
# Abrir PR no GitHub
```

### Remover proteção temporariamente

Se precisar fazer uma mudança urgente:
1. Settings → Branches
2. Editar a regra
3. Temporariamente desmarcar "Do not allow bypassing"
4. Fazer a mudança
5. **IMPORTANTE**: Reativar a proteção imediatamente!

---

## 📚 Referências

- [GitHub Branch Protection](https://docs.github.com/en/repositories/configuring-branches-and-merges-in-your-repository/managing-protected-branches/about-protected-branches)
- [GitHub Actions Status Checks](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/collaborating-on-repositories-with-code-quality-features/about-status-checks)
