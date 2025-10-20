#!/bin/bash

# SmartCheckout - Clean Script
# Uso: ./scripts/clean.sh [infrastructure|<service-name>|all]

set -e

# Navegar para o diretório raiz do projeto
cd "$(dirname "$0")/.."

# Cores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${RED}🧹 SmartCheckout - Clean${NC}"
echo "=========================================="
echo -e "${YELLOW}⚠️  ATENÇÃO: Isso vai remover volumes e dados!${NC}"
echo ""

# Confirmar apenas se for limpeza completa
if [ "${1:-all}" == "all" ]; then
    read -p "Tem certeza que deseja limpar TUDO? (yes/no): " confirm
    if [ "$confirm" != "yes" ]; then
        echo -e "${YELLOW}❌ Operação cancelada.${NC}"
        exit 0
    fi
fi

# Função para limpar infrastructure
clean_infrastructure() {
    echo ""
    echo -e "${RED}🗑️  Limpando Infrastructure...${NC}"
    cd infrastructure
    docker-compose down -v
    cd ..
    echo -e "${GREEN}✅ Infrastructure limpa${NC}"
}

# Função para limpar um serviço específico
clean_service() {
    local service=$1
    if [ -d "services/$service" ]; then
        echo ""
        echo -e "${RED}🗑️  Limpando $service...${NC}"
        cd "services/$service"
        docker-compose down -v
        cd ../..
        echo -e "${GREEN}✅ $service limpo${NC}"
    else
        echo -e "${RED}❌ Serviço '$service' não encontrado${NC}"
        exit 1
    fi
}

# Função para limpar todos os microserviços
clean_all_services() {
    if [ -d "services" ]; then
        for service_dir in services/*/ ; do
            if [ -d "$service_dir" ] && [ -f "${service_dir}docker-compose.yml" ]; then
                service_name=$(basename "$service_dir")
                clean_service "$service_name"
            fi
        done
    fi
}

# Main
case "${1:-all}" in
    infrastructure|infra)
        clean_infrastructure
        ;;
    all)
        clean_all_services
        clean_infrastructure
        echo ""
        echo -e "${RED}🧹 Limpando volumes órfãos...${NC}"
        docker volume ls -q | grep smartcheckout | xargs -r docker volume rm 2>/dev/null || true
        echo ""
        echo -e "${RED}🗑️  Removendo network...${NC}"
        docker network rm smartcheckout-network 2>/dev/null || true
        ;;
    *)
        # Assumir que é um nome de serviço
        clean_service "$1"
        ;;
esac

echo ""
echo -e "${GREEN}✅ Limpeza concluída!${NC}"

