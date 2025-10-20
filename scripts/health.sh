#!/bin/bash

# SmartCheckout - Health Check Script
# Uso: ./scripts/health.sh

# Navegar para o diretório raiz do projeto
cd "$(dirname "$0")/.."

# Cores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}🏥 SmartCheckout - Health Check${NC}"
echo "=========================================="

# 1. Infrastructure
echo ""
echo -e "${BLUE}🏗️  Infrastructure:${NC}"
echo "----------------------------------------"

echo -n "Redis:                  "
if docker exec smartcheckout-redis redis-cli ping 2>/dev/null | grep -q PONG; then
    echo -e "${GREEN}✅ Healthy${NC}"
else
    echo -e "${RED}❌ Unhealthy${NC}"
fi

echo -n "RabbitMQ:               "
if docker exec smartcheckout-rabbitmq rabbitmq-diagnostics -q ping 2>/dev/null; then
    echo -e "${GREEN}✅ Healthy${NC}"
else
    echo -e "${RED}❌ Unhealthy${NC}"
fi

# 2. Microserviços
echo ""
echo -e "${BLUE}🔧 Microservices:${NC}"
echo "----------------------------------------"

if [ -d "services" ]; then
    service_count=0
    for service_dir in services/*/ ; do
        if [ -d "$service_dir" ] && [ -f "${service_dir}docker-compose.yml" ]; then
            service_name=$(basename "$service_dir")
            service_count=$((service_count + 1))
            
            # Container do serviço
            container_name="smartcheckout-${service_name}"
            if docker ps --filter "name=${container_name}" --format "{{.Names}}" 2>/dev/null | grep -q "${container_name}"; then
                echo -e "${service_name}: ${GREEN}✅ Running${NC}"
                
                # PostgreSQL do serviço
                db_container="smartcheckout-postgres-${service_name}"
                if docker ps --filter "name=${db_container}" --format "{{.Names}}" 2>/dev/null | grep -q "${db_container}"; then
                    echo -e "  └─ PostgreSQL: ${GREEN}✅ Healthy${NC}"
                fi
            else
                echo -e "${service_name}: ${YELLOW}⚠️  Not Running${NC}"
            fi
        fi
    done
    
    if [ $service_count -eq 0 ]; then
        echo -e "${YELLOW}⚠️  Nenhum microserviço encontrado${NC}"
    fi
else
    echo -e "${YELLOW}⚠️  Pasta services/ não existe${NC}"
fi

# 3. Resumo de containers
echo ""
echo -e "${BLUE}📦 Todos os containers:${NC}"
echo "----------------------------------------"
docker ps --filter "name=smartcheckout-" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}" 2>/dev/null | head -20 || \
echo -e "${YELLOW}⚠️  Nenhum container rodando${NC}"

echo ""
echo "=========================================="

