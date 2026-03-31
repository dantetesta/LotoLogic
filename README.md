<p align="center">
  <img src="https://dantetesta.com.br/lotolab/loteria-logo.png" width="180" alt="LotoLogic Logo" />
</p>

<h1 align="center">LotoLogic</h1>

<p align="center">
  O laboratório de análise de loterias da CAIXA mais completo para apostadores sérios.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/versão-4.2.5-blue?style=for-the-badge" alt="Versão 4.2.5" />
  <img src="https://img.shields.io/badge/plataforma-Windows%20%7C%20macOS%20%7C%20Linux-lightgrey?style=for-the-badge" alt="Plataforma" />
  <img src="https://img.shields.io/badge/licença-MIT-green?style=for-the-badge" alt="Licença MIT" />
</p>

---

## 📥 Downloads — v4.2.5

| Plataforma | Instalador | Link |
|---|---|---|
| Windows | NSIS Setup (recomendado) | [LotoLogic_4.2.5_x64-setup.exe](https://github.com/dantetesta/LotoLogic/releases/download/v4.2.5/LotoLogic_4.2.5_x64-setup.exe) |
| Windows | MSI | [LotoLogic_4.2.5_x64_en-US.msi](https://github.com/dantetesta/LotoLogic/releases/download/v4.2.5/LotoLogic_4.2.5_x64_en-US.msi) |
| macOS | Apple Silicon (M1/M2/M3) | [LotoLogic_4.2.5_aarch64.dmg](https://github.com/dantetesta/LotoLogic/releases/download/v4.2.5/LotoLogic_4.2.5_aarch64.dmg) |
| macOS | Intel | [LotoLogic_4.2.5_x64.dmg](https://github.com/dantetesta/LotoLogic/releases/download/v4.2.5/LotoLogic_4.2.5_x64.dmg) |
| Linux | Debian/Ubuntu (.deb) | [LotoLogic_4.2.5_amd64.deb](https://github.com/dantetesta/LotoLogic/releases/download/v4.2.5/LotoLogic_4.2.5_amd64.deb) |
| Linux | AppImage (universal) | [LotoLogic_4.2.5_amd64.AppImage](https://github.com/dantetesta/LotoLogic/releases/download/v4.2.5/LotoLogic_4.2.5_amd64.AppImage) |

### Dicas de instalação

**macOS — Apple Silicon (M1/M2/M3):** use o `.dmg` `aarch64`. Se o seu Mac for anterior a 2020 (Intel), use o `.dmg` `x64`.

**Linux — AppImage:** após baixar, dê permissão de execução antes de abrir:
```bash
chmod +x LotoLogic_4.2.5_amd64.AppImage
./LotoLogic_4.2.5_amd64.AppImage
```

---

## O que é o LotoLogic?

O **LotoLogic** é um aplicativo desktop completo para análise estatística e geração inteligente de apostas nas loterias da CAIXA. Desenvolvido com tecnologia moderna (Tauri + React), ele roda 100% no seu computador — sem servidores externos, sem mensalidade, sem rastreamento.

Com mais de 2.990 concursos importados automaticamente e uma engine de análise com mais de 20 algoritmos matemáticos, o LotoLogic é a ferramenta ideal para quem leva as loterias a sério.

---

## 🎰 Loterias Suportadas

- Mega-Sena
- Lotofácil
- Quina
- Lotomania
- Timemania
- Dupla Sena
- Dia de Sorte
- Super Sete
- +Milionária
- Loteca
- Loteria Federal

---

## 🧩 Módulos

### 1. Dashboard
Visão geral dos concursos recentes, estatísticas rápidas e resumo da sua carteira de jogos.

### 2. Concursos
Histórico completo de todos os resultados das loterias suportadas. Consulte, filtre e explore qualquer concurso passado.

### 3. Gerador
Geração de apostas com **7 estratégias distintas**:
- Frequência Alta
- Frequência Baixa
- Alternância Paridade (pares/ímpares)
- Distribuição Positional
- Aleatório Puro
- Híbrido Estatístico
- Baseado em Padrões Históricos

### 4. Logic Pro *(anteriormente LotoCore)*
O coração analítico do LotoLogic. Engine com **mais de 20 algoritmos matemáticos** incluindo análise de frequência, entropia, correlações, ciclos, gaps, análise posicional e muito mais.

### 5. Assistente IA
Integração com **OpenAI (GPT-4)** e **Google Gemini** para análise conversacional, sugestões estratégicas e interpretação dos dados estatísticos em linguagem natural.

### 6. Meus Jogos
Gerencie e acompanhe todos os jogos que você criou. Salve, edite, marque como favorito e visualize o desempenho histórico de cada aposta.

### 7. SuperLab
Otimizador multi-objetivo avançado. Execute simulações e backtests sobre estratégias complexas, combinando múltiplos critérios de seleção simultaneamente.

### 8. Desdobramento
Módulo dedicado ao desdobramento de apostas: gere múltiplas combinações a partir de um conjunto de dezenas selecionadas, maximizando a cobertura com o menor custo possível.

### 9. Configurações
Configure chaves de API (OpenAI, Gemini), preferências de interface, tema (claro/escuro), sincronização de dados e muito mais.

---

## 🔒 Privacidade

O LotoLogic foi desenvolvido com **privacidade por padrão**:

- Todos os dados são armazenados **100% localmente** no seu computador via **SQLite**
- Nenhuma aposta, histórico ou dado pessoal é enviado para servidores externos
- As integrações com IA (OpenAI/Gemini) são opcionais e usam **sua própria chave de API**
- Sem conta, sem cadastro, sem assinatura

---

## 💻 Requisitos do Sistema

| Sistema | Versão Mínima | Arquitetura |
|---|---|---|
| Windows | Windows 10 (64-bit) | x64 |
| macOS | macOS 11 (Big Sur) | Apple Silicon ou Intel x64 |
| Linux | Ubuntu 20.04 / Debian 11 ou equivalente | x64 (amd64) |

---

## 🚀 Como Instalar

### Windows
1. Baixe o instalador `.exe` (NSIS) ou `.msi`
2. Execute o instalador e siga as instruções na tela
3. O LotoLogic será instalado e aparecerá no Menu Iniciar

### macOS
1. Baixe o arquivo `.dmg` correto para o seu Mac (Silicon ou Intel)
2. Abra o `.dmg` e arraste o ícone do LotoLogic para a pasta **Aplicativos**
3. Na primeira execução, clique com o botão direito → **Abrir** (necessário apenas uma vez)

### Linux — AppImage
```bash
# Baixe o AppImage
wget https://github.com/dantetesta/LotoLogic/releases/download/v4.2.5/LotoLogic_4.2.5_amd64.AppImage

# Dê permissão de execução
chmod +x LotoLogic_4.2.5_amd64.AppImage

# Execute
./LotoLogic_4.2.5_amd64.AppImage
```

### Linux — Debian/Ubuntu (.deb)
```bash
# Baixe o pacote .deb
wget https://github.com/dantetesta/LotoLogic/releases/download/v4.2.5/LotoLogic_4.2.5_amd64.deb

# Instale via dpkg
sudo dpkg -i LotoLogic_4.2.5_amd64.deb

# Se houver dependências faltando
sudo apt-get install -f
```

---

## 📣 Anuncie no LotoLogic

O LotoLogic é usado por apostadores ativos e apaixonados por loterias. Se você tem um produto ou serviço relevante para esse público, sua marca pode estar dentro do app — direto na frente de quem mais importa para você.

📲 Fale comigo no WhatsApp: [+55 19 99802-1956](https://wa.me/5519998021956)

---

## 💸 Apoie o Projeto via PIX

O LotoLogic é desenvolvido de forma independente, com muito café e muitas horas de trabalho. Se ele te ajudou a apostar com mais estratégia — e especialmente se um dia você ganhar na loteria com a ajuda dele — lembre-se de ser generoso com quem te ajudou! 😄

💸 **Chave PIX:** `dante.testa@gmail.com`

---

## 👨‍💻 Autor

<p align="center">
  <a href="https://www.youtube.com/channel/UCcYUV_knziHoPBFOwO1BbeA">
    <img src="https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube&logoColor=white" alt="YouTube" />
  </a>
  &nbsp;
  <a href="https://www.instagram.com/dantetesta_/">
    <img src="https://img.shields.io/badge/Instagram-E4405F?style=for-the-badge&logo=instagram&logoColor=white" alt="Instagram" />
  </a>
</p>

<p align="center">Se o LotoLogic te ajudou, me siga nas redes — isso ajuda muito a manter o projeto vivo! 🙏</p>

---

## 💬 Comunidade

💬 **Grupo no Telegram:** [Entrar no grupo](https://t.me/+OrYQ9YkFxN8xZDhh)

Tire dúvidas, compartilhe estratégias e acompanhe as novidades do LotoLogic junto com outros apostadores.

---

## 📋 Changelog

### v4.2.5
- Rebranding completo: **LotoLab → LotoLogic**, novo logo e identidade visual

### v4.2.4
- Fix importação SQL: 2930 → **2990 concursos** importados corretamente

### v4.2.3
- **SuperLab Otimizador Multi-objetivo** reescrito do zero com melhor performance
- Novo módulo **Desdobramento**
- Integração com **Google Gemini** como alternativa ao OpenAI

### v4.0.0
- Reescrita completa do frontend com React + Tauri
- **LotoCore** (agora Logic Pro) com 20+ algoritmos de análise matemática

---

<p align="center">
  LotoLogic v4.2.5 — Feito com ❤️ para apostadores sérios
</p>
