#!/bin/bash

# SmartCheckout - Stop Script
# Uso: ./scripts/stop.sh [infrastructure|<service-name>|all]

set -e

# Navegar para o diretório raiz do projeto
cd "$(dirname "$0")/.."

# Cores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

# Função para parar infrastructure
stop_infrastructure() {
    echo -e "${BLUE}🏗️  Parando Infrastructure...${NC}"
    cd infrastructure
    docker-compose down
    cd ..
    echo -e "${GREEN}✅ Infrastructure parada${NC}"
}

# Função para parar um serviço específico
stop_service() {
    local service=$1
    if [ -d "services/$service" ]; then
        echo -e "${BLUE}📦 Parando $service...${NC}"
        cd "services/$service"
        docker-compose down
        cd ../..
        echo -e "${GREEN}✅ $service parado${NC}"
    else
        echo -e "${RED}❌ Serviço '$service' não encontrado${NC}"
        exit 1
    fi
}

# Função para parar todos os microserviços
stop_all_services() {
    if [ -d "services" ]; then
        for service_dir in services/*/ ; do
            if [ -d "$service_dir" ] && [ -f "${service_dir}docker-compose.yml" ]; then
                service_name=$(basename "$service_dir")
                stop_service "$service_name"
            fi
        done
    fi
}

# Main
echo -e "${YELLOW}🛑 SmartCheckout - Stop${NC}"
echo "======================================"
echo ""

case "${1:-all}" in
    infrastructure|infra)
        stop_infrastructure
        ;;
    all)
        stop_all_services
        echo ""
        stop_infrastructure
        ;;
    *)
        # Assumir que é um nome de serviço
        stop_service "$1"
        ;;
esac

echo ""
echo -e "${GREEN}✅ Parado com sucesso!${NC}"

