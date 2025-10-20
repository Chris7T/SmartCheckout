# 🛒 SmartCheckout - Sistema de Vendas com Microserviços

---

## 🔧 Stack Tecnológica

- **Backend**: Laravel (PHP 8.x)
- **Banco de Dados**: PostgreSQL
- **Cache/Filas**: Redis
- **Mensageria**: RabbitMQ
- **Containers**: Docker
---

## 🎯 Resumo do Projeto

Sistema de vendas online com microserviços em Laravel.

**Usuários**: Clientes (fazem pedidos) e Funcionários (gerenciam produtos/estoque).

**Microserviços**:
1. **Customer Service**: Clientes, Funcionários, Pedidos e **autenticação JWT**
2. **Product Service**: Produtos e estoque (usa auth do Customer)
3. **API Gateway**: Roteamento e validação de tokens
4. **Payment Service**: Processamento de pagamentos
5. **Search Service**: Busca de produtos (Elasticsearch)
6. **Chat Service**: Atendimento em tempo real
7. **Observabilidade**: Prometheus, Loki, Grafana, Tempo

Comunicação via **RabbitMQ** (assíncrona) e **HTTP** (síncrona).

---

## 📂 Estrutura

```
smartcheckout/
├── infrastructure/          # Redis + RabbitMQ (compartilhados)
│   └── docker-compose.yml
├── services/
│   ├── customer-service/   # Clientes + Funcionários + Pedidos + Auth JWT
│   ├── product-service/    # Produtos + Estoque
│   ├── api-gateway/        # Gateway
│   ├── payment-service/    # Pagamentos
│   ├── search-service/     # Busca Elasticsearch
│   └── chat-service/       # Chat
├── scripts/                # Scripts de gerenciamento
│   ├── start.sh
│   ├── stop.sh
│   ├── logs.sh
│   ├── health.sh
│   └── clean.sh
└── README.md
```

---

## 🚀 Como Usar

### 1. Tornar scripts executáveis (primeira vez)
```bash
chmod +x scripts/*.sh
```

### 2. Iniciar
```bash
# Tudo (infrastructure + microserviços)
./scripts/start.sh

# Apenas infrastructure
./scripts/start.sh infrastructure

# Apenas um serviço específico
./scripts/start.sh product-service
```

### 3. Ver logs
```bash
./scripts/logs.sh                    # Todos
./scripts/logs.sh product-service    # Serviço específico
./scripts/logs.sh redis              # Container específico
```

### 4. Verificar saúde
```bash
./scripts/health.sh
```

### 5. Parar
```bash
./scripts/stop.sh                    # Tudo
./scripts/stop.sh product-service    # Serviço específico
```

### 6. Limpar dados (remove volumes)
```bash
./scripts/clean.sh                   # Tudo
./scripts/clean.sh product-service   # Serviço específico
```

---

## 📊 Status do Projeto

### ✅ Fase 1: Infrastructure (Concluída)
- [x] Docker Compose (Redis + RabbitMQ)
- [x] Scripts de gerenciamento
- [x] Network compartilhada
- [x] Estrutura base

### 🔄 Fase 2: Customer Service 
- [ ] Setup Laravel + Docker
- [ ] PostgreSQL dedicado
- [ ] **Autenticação JWT** (gera tokens)
- [ ] CRUD de Clientes
- [ ] CRUD de Funcionários
- [ ] Sistema de Pedidos
- [ ] API RESTful

### ⏳ Fase 3: Product Service
- [ ] Setup Laravel + Docker
- [ ] PostgreSQL dedicado
- [ ] CRUD de Produtos
- [ ] Controle de Estoque

### ⏳ Fase 4: API Gateway **JWT Auth**
- [ ] Setup Laravel
- [ ] Roteamento (Customer + Product)
- [ ] Validação de JWT (valida tokens do Customer)
- [ ] Rate limiting + CORS

### ⏳ Fase 5: Payment Service
- [ ] Setup Laravel + Docker
- [ ] PostgreSQL dedicado
- [ ] Processamento de pagamentos
- [ ] Integração via RabbitMQ

### ⏳ Fase 6: Search Service
- [ ] Elasticsearch na infrastructure
- [ ] Indexação de produtos
- [ ] Busca full-text

### ⏳ Fase 7: Chat Service
- [ ] Setup Laravel + WebSockets
- [ ] Chat em tempo real
- [ ] Atendimento ao cliente

### ⏳ Fase 8: Observabilidade
- [ ] Prometheus + Grafana
- [ ] Loki (logs)
- [ ] Tempo (tracing)


---

## 🌐 Serviços Disponíveis

Após iniciar a infrastructure:
- **RabbitMQ UI**: http://localhost:15672 (admin/admin123)

---
