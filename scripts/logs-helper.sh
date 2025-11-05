#!/bin/bash

# Script auxiliar para análise de logs dos containers
# Fornece comandos úteis para visualizar e analisar logs

# Cores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

show_menu() {
    clear
    echo "=================================================="
    echo -e "${GREEN}📝 Logs Helper - Análise de Logs${NC}"
    echo "=================================================="
    echo ""
    echo "1. Ver logs do Nginx (Web Server)"
    echo "2. Ver logs do PHP (Aplicação)"
    echo "3. Ver logs do MySQL (Banco de Dados)"
    echo "4. Ver logs de TODOS os containers"
    echo "5. Buscar por erros (últimas 24h)"
    echo "6. Buscar por warnings (últimas 24h)"
    echo "7. Buscar texto específico"
    echo "8. Ver logs em tempo real (tail -f)"
    echo "9. Exportar logs para arquivo"
    echo "10. Limpar logs antigos"
    echo "0. Sair"
    echo ""
    echo -e "${YELLOW}Escolha uma opção:${NC} "
}

view_nginx_logs() {
    echo -e "${BLUE}📋 Logs do Nginx (últimas 50 linhas):${NC}"
    docker logs --tail 50 mvc_app_web
    echo ""
    read -p "Pressione Enter para continuar..."
}

view_php_logs() {
    echo -e "${BLUE}📋 Logs do PHP (últimas 50 linhas):${NC}"
    docker logs --tail 50 mvc_app_php
    echo ""
    read -p "Pressione Enter para continuar..."
}

view_mysql_logs() {
    echo -e "${BLUE}📋 Logs do MySQL (últimas 50 linhas):${NC}"
    docker logs --tail 50 mvc_app_db
    echo ""
    read -p "Pressione Enter para continuar..."
}

view_all_logs() {
    echo -e "${BLUE}📋 Logs de TODOS os containers:${NC}"
    echo ""
    echo -e "${YELLOW}=== NGINX ===${NC}"
    docker logs --tail 20 mvc_app_web
    echo ""
    echo -e "${YELLOW}=== PHP ===${NC}"
    docker logs --tail 20 mvc_app_php
    echo ""
    echo -e "${YELLOW}=== MySQL ===${NC}"
    docker logs --tail 20 mvc_app_db
    echo ""
    read -p "Pressione Enter para continuar..."
}

search_errors() {
    echo -e "${RED}🔍 Buscando por erros (últimas 24h):${NC}"
    echo ""
    echo -e "${YELLOW}=== NGINX ===${NC}"
    docker logs --since 24h mvc_app_web 2>&1 | grep -i error | tail -20
    echo ""
    echo -e "${YELLOW}=== PHP ===${NC}"
    docker logs --since 24h mvc_app_php 2>&1 | grep -i error | tail -20
    echo ""
    echo -e "${YELLOW}=== MySQL ===${NC}"
    docker logs --since 24h mvc_app_db 2>&1 | grep -i error | tail -20
    echo ""
    read -p "Pressione Enter para continuar..."
}

search_warnings() {
    echo -e "${YELLOW}⚠️  Buscando por warnings (últimas 24h):${NC}"
    echo ""
    echo -e "${YELLOW}=== NGINX ===${NC}"
    docker logs --since 24h mvc_app_web 2>&1 | grep -i warning | tail -20
    echo ""
    echo -e "${YELLOW}=== PHP ===${NC}"
    docker logs --since 24h mvc_app_php 2>&1 | grep -i warning | tail -20
    echo ""
    echo -e "${YELLOW}=== MySQL ===${NC}"
    docker logs --since 24h mvc_app_db 2>&1 | grep -i warning | tail -20
    echo ""
    read -p "Pressione Enter para continuar..."
}

search_custom() {
    echo -e "${BLUE}🔍 Buscar texto específico:${NC}"
    read -p "Digite o texto a buscar: " search_text
    
    if [ -z "$search_text" ]; then
        echo -e "${RED}Texto vazio!${NC}"
        read -p "Pressione Enter para continuar..."
        return
    fi
    
    echo ""
    echo -e "${YELLOW}=== NGINX ===${NC}"
    docker logs mvc_app_web 2>&1 | grep -i "$search_text" | tail -20
    echo ""
    echo -e "${YELLOW}=== PHP ===${NC}"
    docker logs mvc_app_php 2>&1 | grep -i "$search_text" | tail -20
    echo ""
    echo -e "${YELLOW}=== MySQL ===${NC}"
    docker logs mvc_app_db 2>&1 | grep -i "$search_text" | tail -20
    echo ""
    read -p "Pressione Enter para continuar..."
}

tail_logs() {
    echo -e "${BLUE}📊 Logs em tempo real - Escolha o container:${NC}"
    echo "1. Nginx"
    echo "2. PHP"
    echo "3. MySQL"
    echo ""
    read -p "Escolha: " choice
    
    case $choice in
        1) echo -e "${GREEN}Acompanhando Nginx (Ctrl+C para sair)...${NC}"; docker logs -f mvc_app_web ;;
        2) echo -e "${GREEN}Acompanhando PHP (Ctrl+C para sair)...${NC}"; docker logs -f mvc_app_php ;;
        3) echo -e "${GREEN}Acompanhando MySQL (Ctrl+C para sair)...${NC}"; docker logs -f mvc_app_db ;;
        *) echo -e "${RED}Opção inválida!${NC}" ;;
    esac
    
    echo ""
    read -p "Pressione Enter para continuar..."
}

export_logs() {
    TIMESTAMP=$(date +%Y%m%d_%H%M%S)
    EXPORT_DIR="logs_export_$TIMESTAMP"
    
    mkdir -p "$EXPORT_DIR"
    
    echo -e "${BLUE}📦 Exportando logs para: $EXPORT_DIR/${NC}"
    
    docker logs mvc_app_web > "$EXPORT_DIR/nginx.log" 2>&1
    docker logs mvc_app_php > "$EXPORT_DIR/php.log" 2>&1
    docker logs mvc_app_db > "$EXPORT_DIR/mysql.log" 2>&1
    
    echo ""
    echo -e "${GREEN}✅ Logs exportados com sucesso!${NC}"
    echo ""
    ls -lh "$EXPORT_DIR"
    echo ""
    read -p "Pressione Enter para continuar..."
}

clean_old_logs() {
    echo -e "${YELLOW}⚠️  ATENÇÃO: Esta ação irá limpar os logs dos containers!${NC}"
    echo -e "${RED}Os logs serão perdidos permanentemente.${NC}"
    echo ""
    read -p "Tem certeza? (digite 'sim' para confirmar): " confirm
    
    if [ "$confirm" = "sim" ]; then
        echo ""
        echo -e "${BLUE}🧹 Limpando logs...${NC}"
        
        # Truncar logs (não reinicia containers)
        docker exec mvc_app_web truncate -s 0 /var/log/nginx/access.log 2>/dev/null || true
        docker exec mvc_app_web truncate -s 0 /var/log/nginx/error.log 2>/dev/null || true
        
        echo ""
        echo -e "${GREEN}✅ Logs limpos!${NC}"
        echo ""
        echo "Nota: Logs do Docker não foram removidos."
        echo "Para limpar completamente, reinicie os containers:"
        echo "  docker-compose restart"
    else
        echo -e "${YELLOW}Operação cancelada.${NC}"
    fi
    
    echo ""
    read -p "Pressione Enter para continuar..."
}

# Menu principal
while true; do
    show_menu
    read choice
    
    case $choice in
        1) view_nginx_logs ;;
        2) view_php_logs ;;
        3) view_mysql_logs ;;
        4) view_all_logs ;;
        5) search_errors ;;
        6) search_warnings ;;
        7) search_custom ;;
        8) tail_logs ;;
        9) export_logs ;;
        10) clean_old_logs ;;
        0) echo -e "${GREEN}Até logo!${NC}"; exit 0 ;;
        *) echo -e "${RED}Opção inválida!${NC}"; sleep 2 ;;
    esac
done
