#!/bin/bash

# SmartCheckout - Start Script
# Uso: ./scripts/start.sh [infrastrucutre|<service-name>|all]

set -e

# Navegar para o diretório raiz do projeto
cd "$(dirname "$0")/.."

# Cores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Verificar se o Docker está rodando
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}❌ Docker não está rodando. Por favor, inicie o Docker primeiro.${NC}"
    exit 1
fi

# Função para iniciar infrastructure
start_infrastructure() {
    echo -e "${BLUE}🏗️  Iniciando Infrastructure (Redis, RabbitMQ)...${NC}"
    cd infrastructure
    docker-compose up -d
    cd ..
    echo -e "${GREEN}✅ Infrastructure iniciada${NC}"
}

# Função para iniciar um serviço específico
start_service() {
    local service=$1
    if [ -d "services/$service" ]; then
        echo -e "${BLUE}📦 Iniciando $service...${NC}"
        cd "services/$service"
        docker-compose up -d
        cd ../..
        echo -e "${GREEN}✅ $service iniciado${NC}"
    else
        echo -e "${RED}❌ Serviço '$service' não encontrado${NC}"
        exit 1
    fi
}

# Função para iniciar todos os microserviços
start_all_services() {
    if [ -d "services" ]; then
        for service_dir in services/*/ ; do
            if [ -d "$service_dir" ] && [ -f "${service_dir}docker-compose.yml" ]; then
                service_name=$(basename "$service_dir")
                start_service "$service_name"
            fi
        done
    fi
}

# Main
echo -e "${GREEN}🚀 SmartCheckout - Start${NC}"
echo "======================================"
echo ""

case "${1:-all}" in
    infrastructure|infra)
        start_infrastructure
        ;;
    all)
        start_infrastructure
        echo ""
        start_all_services
        ;;
    *)
        # Assumir que é um nome de serviço
        start_infrastructure
        echo ""
        start_service "$1"
        ;;
esac

echo ""
echo -e "${GREEN}✅ Iniciado com sucesso!${NC}"
echo ""
echo -e "${BLUE}📋 Comandos úteis:${NC}"
echo "  ./scripts/health.sh          - Verificar saúde"
echo "  ./scripts/logs.sh [serviço]  - Ver logs"
echo "  ./scripts/stop.sh [serviço]  - Parar serviços"
echo ""
echo -e "${BLUE}🌐 Serviços:${NC}"
echo "  RabbitMQ UI: http://localhost:15672 (admin/admin123)"

