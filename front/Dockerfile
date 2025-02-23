# Use a lightweight Debian base image
FROM debian:bookworm-slim AS base

# Set environment variables
ENV NVM_DIR="/usr/local/nvm"
ENV NODE_VERSION="22.0.0"

# Set working directory
WORKDIR /app

# Install required dependencies
RUN apt-get update && apt-get install -y \
    curl ca-certificates bash git \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p $NVM_DIR

# Install NVM
RUN curl -fsSL https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.4/install.sh | bash

# Load NVM and install Node.js
RUN bash -c "source $NVM_DIR/nvm.sh && nvm install $NODE_VERSION && nvm use $NODE_VERSION && nvm alias default $NODE_VERSION"

# Add Node.js & NPM to PATH
ENV PATH="$NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH"

# Verify Node.js installation
RUN node -v && npm -v

# Copy package.json and package-lock.json first for better caching
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the app
COPY . .

# Expose Nuxt's default port
EXPOSE 3000

# Development Stage
FROM base AS development

USER ${USER}

CMD ["npm", "run", "dev"]