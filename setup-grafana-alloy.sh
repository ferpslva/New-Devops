#!/bin/bash

# =============================================================================
# Script de Instalação do Grafana Alloy
# =============================================================================
# Este script instala e configura o Grafana Alloy para enviar métricas e logs
# para o Grafana Cloud.
#
# Consumo de RAM: 40-70MB (4-7% do servidor)
# Intervalo de coleta: 120s (otimizado para baixo consumo)
# =============================================================================

echo "=============================================================="
echo "🚀 Instalação do Grafana Alloy (Versão Otimizada)"
echo "=============================================================="
echo ""

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Verificar se está rodando como root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}❌ Por favor, execute como root ou com sudo${NC}"
    exit 1
fi

echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  PARTE 1: OBTER CREDENCIAIS DO GRAFANA CLOUD${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo ""

echo -e "${YELLOW}📋 Antes de continuar, você precisa obter as credenciais do Grafana Cloud:${NC}"
echo ""
echo "1. Acesse: https://grafana.com (faça login)"
echo "2. Menu lateral → Connections"
echo ""
echo "Para PROMETHEUS (Métricas):"
echo "   - Busque por: 'Prometheus'"
echo "   - Copie: URL, Username, Password"
echo ""
echo "Para LOKI (Logs):"
echo "   - Busque por: 'Loki'"
echo "   - Copie: URL (adicione /loki/api/v1/push no final), Username, Password"
echo ""
read -p "Pressione Enter quando tiver as credenciais em mãos..."

echo ""
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  PARTE 2: INSTALAR GRAFANA ALLOY${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo ""

echo -e "${GREEN}📦 Etapa 1: Adicionando repositório do Grafana...${NC}"

# Criar diretório para chaves
mkdir -p /etc/apt/keyrings/

# Baixar e adicionar chave GPG
echo "   → Baixando chave GPG..."
wget -q -O - https://apt.grafana.com/gpg.key | gpg --dearmor | tee /etc/apt/keyrings/grafana.gpg > /dev/null

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Erro ao baixar chave GPG${NC}"
    exit 1
fi

# Adicionar repositório
echo "   → Adicionando repositório..."
echo "deb [signed-by=/etc/apt/keyrings/grafana.gpg] https://apt.grafana.com stable main" | tee /etc/apt/sources.list.d/grafana.list > /dev/null

echo ""
echo -e "${GREEN}📦 Etapa 2: Atualizando lista de pacotes...${NC}"
apt-get update

echo ""
echo -e "${GREEN}📦 Etapa 3: Instalando Grafana Alloy...${NC}"
apt-get install -y alloy

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Erro ao instalar Grafana Alloy${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}✅ Grafana Alloy instalado com sucesso!${NC}"

# Verificar versão
ALLOY_VERSION=$(alloy --version 2>&1 | head -n 1)
echo "   Versão: $ALLOY_VERSION"

echo ""
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  PARTE 3: CONFIGURAR GRAFANA ALLOY${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo ""

# Criar diretório de configuração
mkdir -p /etc/alloy

echo -e "${YELLOW}📝 Agora vamos criar o arquivo de configuração...${NC}"
echo ""
echo "Por favor, forneça as seguintes informações:"
echo ""

# Coletar credenciais do Prometheus
echo -e "${GREEN}━━━ PROMETHEUS (Métricas) ━━━${NC}"
read -p "Prometheus URL (ex: https://prometheus-prod-XX.grafana.net/api/prom/push): " PROMETHEUS_URL
read -p "Prometheus Username/ID: " PROMETHEUS_USER
read -sp "Prometheus Password/Token: " PROMETHEUS_PASSWORD
echo ""

# Coletar credenciais do Loki (OPCIONAL)
echo ""
echo -e "${GREEN}━━━ LOKI (Logs) - OPCIONAL ━━━${NC}"
echo -e "${YELLOW}💡 Dica: Pressione Enter (deixe em branco) para pular o Loki${NC}"
echo ""
read -p "Loki URL (ex: https://logs-prod-XX.grafana.net/loki/api/v1/push) [OPCIONAL]: " LOKI_URL
read -p "Loki Username/ID [OPCIONAL]: " LOKI_USER
read -sp "Loki Password/Token [OPCIONAL]: " LOKI_PASSWORD
echo ""

# Validar inputs do Prometheus (obrigatórios)
if [ -z "$PROMETHEUS_URL" ] || [ -z "$PROMETHEUS_USER" ] || [ -z "$PROMETHEUS_PASSWORD" ]; then
    echo -e "${RED}❌ Erro: Credenciais do Prometheus são obrigatórias!${NC}"
    exit 1
fi

# Verificar se Loki será configurado
CONFIGURE_LOKI=false
if [ -n "$LOKI_URL" ] && [ -n "$LOKI_USER" ] && [ -n "$LOKI_PASSWORD" ]; then
    CONFIGURE_LOKI=true
    echo ""
    echo -e "${GREEN}✅ Loki será configurado${NC}"
else
    echo ""
    echo -e "${YELLOW}ℹ️  Loki não será configurado (use Dozzle para logs)${NC}"
fi

echo ""
echo -e "${GREEN}📝 Criando arquivo de configuração...${NC}"

# Criar arquivo config.alloy base (apenas Prometheus)
cat > /etc/alloy/config.alloy << 'EOF'
// =============================================================================
// GRAFANA ALLOY - CONFIGURAÇÃO OTIMIZADA (BAIXO CONSUMO)
// Coleta métricas do servidor
// Consumo estimado: 40-70MB RAM (4-7% do servidor)
// Intervalo de coleta: 120s (2 minutos)
// =============================================================================

// -----------------------------------------------------------------------------
// PROMETHEUS - Coleta de Métricas do Sistema
// -----------------------------------------------------------------------------

// Exportador de métricas do sistema (apenas essenciais)
prometheus.exporter.unix "system_metrics" {
  set_collectors = ["cpu", "meminfo", "diskstats", "loadavg", "netdev"]
}

// Scrape (coletar) as métricas do exportador
prometheus.scrape "default" {
  targets = prometheus.exporter.unix.system_metrics.targets
  forward_to = [prometheus.remote_write.grafana_cloud.receiver]
  
  scrape_interval = "120s"
  scrape_timeout = "30s"
}

// Enviar métricas para Grafana Cloud
prometheus.remote_write "grafana_cloud" {
  endpoint {
    url = "PROMETHEUS_URL_PLACEHOLDER"
    
    basic_auth {
      username = "PROMETHEUS_USER_PLACEHOLDER"
      password = "PROMETHEUS_PASSWORD_PLACEHOLDER"
    }
    
    queue_config {
      capacity = 1000
      max_samples_per_send = 500
      batch_send_deadline = "5s"
    }
  }
}
EOF

# Substituir placeholders do Prometheus
sed -i "s|PROMETHEUS_URL_PLACEHOLDER|$PROMETHEUS_URL|g" /etc/alloy/config.alloy
sed -i "s|PROMETHEUS_USER_PLACEHOLDER|$PROMETHEUS_USER|g" /etc/alloy/config.alloy
sed -i "s|PROMETHEUS_PASSWORD_PLACEHOLDER|$PROMETHEUS_PASSWORD|g" /etc/alloy/config.alloy

# Se Loki foi configurado, adicionar seção Loki
if [ "$CONFIGURE_LOKI" = true ]; then
    cat >> /etc/alloy/config.alloy << 'EOF'

// -----------------------------------------------------------------------------
// DOCKER - Descoberta de Containers
// -----------------------------------------------------------------------------

discovery.docker "containers" {
  host = "unix:///var/run/docker.sock"
  refresh_interval = "60s"
}

// -----------------------------------------------------------------------------
// LOKI - Coleta de Logs dos Containers
// -----------------------------------------------------------------------------

loki.source.docker "docker_logs" {
  host = "unix:///var/run/docker.sock"
  targets = discovery.docker.containers.targets
  forward_to = [loki.write.grafana_cloud.receiver]
  
  relabel_rules = loki.relabel.docker_labels.rules
  max_read_size = "1MB"
}

loki.relabel "docker_labels" {
  forward_to = []
  
  rule {
    source_labels = ["__meta_docker_container_name"]
    target_label  = "container"
  }
  
  rule {
    source_labels = ["__meta_docker_container_log_stream"]
    target_label  = "stream"
  }
}

loki.write "grafana_cloud" {
  endpoint {
    url = "LOKI_URL_PLACEHOLDER"
    
    basic_auth {
      username = "LOKI_USER_PLACEHOLDER"
      password = "LOKI_PASSWORD_PLACEHOLDER"
    }
    
    external_labels = {
      cluster = "localweb-vps",
      environment = "production",
    }
  }
}
EOF

    # Substituir placeholders do Loki
    sed -i "s|LOKI_URL_PLACEHOLDER|$LOKI_URL|g" /etc/alloy/config.alloy
    sed -i "s|LOKI_USER_PLACEHOLDER|$LOKI_USER|g" /etc/alloy/config.alloy
    sed -i "s|LOKI_PASSWORD_PLACEHOLDER|$LOKI_PASSWORD|g" /etc/alloy/config.alloy
fi

# Validar configuração
echo ""
echo -e "${GREEN}🔍 Validando configuração...${NC}"
alloy fmt /etc/alloy/config.alloy > /dev/null 2>&1

if [ $? -ne 0 ]; then
    echo -e "${YELLOW}⚠️  Aviso: Formato da configuração pode ter problemas${NC}"
    echo -e "${YELLOW}   O serviço tentará iniciar mesmo assim...${NC}"
fi

echo ""
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  PARTE 4: INICIAR SERVIÇO${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo ""

echo -e "${GREEN}▶️  Habilitando serviço...${NC}"
systemctl enable alloy

echo -e "${GREEN}▶️  Iniciando Grafana Alloy...${NC}"
systemctl start alloy

# Aguardar 3 segundos
sleep 3

# Verificar status
if systemctl is-active --quiet alloy; then
    echo -e "${GREEN}✅ Serviço iniciado com sucesso!${NC}"
else
    echo -e "${RED}❌ Erro ao iniciar serviço${NC}"
    echo ""
    echo "Logs do erro:"
    journalctl -u alloy -n 20 --no-pager
    exit 1
fi

echo ""
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  PARTE 5: VERIFICAÇÃO${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo ""

echo -e "${GREEN}🔍 Status do serviço:${NC}"
systemctl status alloy --no-pager -l | head -n 10

echo ""
echo -e "${GREEN}💾 Consumo de memória:${NC}"
ps aux | grep alloy | grep -v grep | awk '{print "   RAM: " $6/1024 " MB (" $4 "%)"}'

echo ""
echo -e "${GREEN}═══════════════════════════════════════════════════════${NC}"
echo -e "${GREEN}  ✅ INSTALAÇÃO CONCLUÍDA COM SUCESSO!${NC}"
echo -e "${GREEN}═══════════════════════════════════════════════════════${NC}"
echo ""

echo -e "${YELLOW}📊 Próximos passos:${NC}"
echo ""
echo "1. Aguarde 2-3 minutos para os dados começarem a aparecer"
echo ""
echo "2. Acesse seu Grafana Cloud:"
echo "   https://grafana.com"
echo ""
echo "3. Verificar métricas:"
echo "   Menu → Explore → Metrics/Prometheus"
echo "   Query: up"
echo ""
echo "4. Verificar logs:"
echo "   Menu → Explore → Logs/Loki"
echo "   Query: {container=\"mvc_app_web\"}"
echo ""
echo "5. Importar dashboard pronto:"
echo "   Menu → Dashboards → New → Import"
echo "   ID: 1860"
echo ""
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  COMANDOS ÚTEIS${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════${NC}"
echo ""
echo "Ver status:          sudo systemctl status alloy"
echo "Ver logs:            sudo journalctl -u alloy -f"
echo "Reiniciar:           sudo systemctl restart alloy"
echo "Parar:               sudo systemctl stop alloy"
echo "Verificar config:    sudo alloy fmt /etc/alloy/config.alloy"
echo ""
echo -e "${GREEN}🎉 Monitoramento configurado com sucesso!${NC}"
echo ""
