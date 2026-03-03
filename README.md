# VibeLLMPC

A plug-and-play private AI server for Raspberry Pi 5, built on top of [VibeCodePC](https://vibecodepc.com).

Flash an SD card, plug it in, and you have a self-hosted Ollama + Open WebUI + n8n stack running on your local network — with optional Cloudflare tunnel access from anywhere.

---

## What it is

VibeLLMPC turns a Raspberry Pi 5 into a **private AI workstation** with:

- **Ollama** — run large language models locally (Llama, Mistral, Gemma, Phi, DeepSeek, and more)
- **Open WebUI** — a polished ChatGPT-like interface connected to your local Ollama
- **n8n** — visual workflow automation with built-in AI agent nodes
- **Device app** — a Laravel wizard that guides you through model selection and tunnel setup
- **Cloudflare tunnel** — optional secure remote access with no port forwarding

---

## Monorepo structure

```
vibellmpc/
├── device/          # Laravel app — setup wizard, dashboard, Ollama management
├── cloud/           # Laravel app — cloud pairing & device registry (optional)
├── packages/
│   └── vibellmpc-common/   # Shared DTOs, enums, and contracts
├── .github/
│   └── workflows/   # CI/CD pipelines
└── docker-compose.yml       # Full local dev stack
```

---

## Services

| Service       | Image / Build                          | Port (host) | URL                        |
|---------------|----------------------------------------|-------------|----------------------------|
| device        | `./device/Dockerfile`                  | 8081        | http://localhost:8081      |
| ollama        | `ollama/ollama:latest`                 | 11434       | http://localhost:11434     |
| open-webui    | `ghcr.io/open-webui/open-webui:main`   | 3000        | http://localhost:3000      |
| n8n           | `n8nio/n8n:latest`                     | 5678        | http://localhost:5678      |
| cloud         | `./cloud/Dockerfile`                   | 8082        | http://localhost:8082      |
| cloudflared   | `device/docker/cloudflared.Dockerfile` | —           | tunnel (no exposed port)   |
| redis-device  | `valkey/valkey:8-alpine`               | 6380        | —                          |

---

## Quick start

### Prerequisites
- Docker + Docker Compose
- (On Linux) export `DOCKER_GID=$(getent group docker | cut -d: -f3)` before running

### Run the full stack

```bash
git clone https://github.com/your-org/vibellmpc.git
cd vibellmpc

# Start all services
docker compose up -d

# Run device DB migrations
docker compose exec device php artisan migrate --force

# Generate a device identity
docker compose exec device php artisan device:generate-id
```

Then open:
- **Device wizard** → http://localhost:8081
- **Open WebUI** → http://localhost:3000
- **n8n** → http://localhost:5678

### Pull your first model

From the setup wizard, select a model (e.g. Llama 3.2 8B — recommended) and hit **Download**. The model pulls in the background via the `PullModelJob` queue worker.

Or pull directly from the CLI:

```bash
docker compose exec ollama ollama pull llama3.2:8b
```

---

## Development

```bash
# Device app
cd device
composer install
npm install && npm run dev

# Run tests
docker compose exec device php artisan test
```

---

## Firmware version

`vllm-1.0.0`
