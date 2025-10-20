#!/bin/bash

# SmartCheckout - Logs Script
# Uso: ./scripts/logs.sh [infrastructure|<service-name>|<container-name>]

# Navegar para o diretório raiz do projeto
cd "$(dirname "$0")/.."

# Cores
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

if [ -z "$1" ]; then
    echo -e "${BLUE}📋 Mostrando logs de TODOS os containers SmartCheckout...${NC}"
    echo "Pressione Ctrl+C para sair"
    echo ""
    docker logs -f $(docker ps --filter "name=smartcheckout-" --format "{{.Names}}" | head -1) 2>/dev/null || \
    docker ps --filter "name=smartcheckout-" --format "{{.Names}}" | xargs -I {} docker logs -f {} 2>/dev/null || \
    echo -e "${YELLOW}⚠️  Nenhum container em execução${NC}"
elif [ "$1" == "infrastructure" ] || [ "$1" == "infra" ]; then
    echo -e "${BLUE}📋 Mostrando logs da Infrastructure...${NC}"
    echo "Pressione Ctrl+C para sair"
    echo ""
    cd infrastructure
    docker-compose logs -f
elif [ -d "services/$1" ]; then
    echo -e "${BLUE}📋 Mostrando logs de $1...${NC}"
    echo "Pressione Ctrl+C para sair"
    echo ""
    cd "services/$1"
    docker-compose logs -f
else
    # Tentar como nome de container direto
    echo -e "${BLUE}📋 Mostrando logs de $1...${NC}"
    echo "Pressione Ctrl+C para sair"
    echo ""
    docker logs -f "smartcheckout-$1" 2>/dev/null || \
    docker logs -f "$1" 2>/dev/null || \
    echo -e "${YELLOW}❌ Container '$1' não encontrado${NC}"
fi

