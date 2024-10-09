# Usando a imagem oficial do Node.js
FROM node:16

# Definindo o diretório de trabalho
WORKDIR /usr/src/app

# Copiando os arquivos de configuração
COPY package*.json ./

# Instalando dependências
RUN npm install

# Copiando o restante do código
COPY . .

# Expondo a porta da aplicação
EXPOSE 3000

# Executando a aplicação
CMD ["node", "server.js"]
