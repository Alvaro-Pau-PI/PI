FROM node:20-alpine

# Install json-server globally (pinned to stable version 0.17.4)
RUN npm install -g json-server@0.17.4

# Set working directory
WORKDIR /app

# Expose port
EXPOSE 3000

# Default command
CMD ["json-server", "/data/db.json", "--host", "0.0.0.0", "--port", "3000", "--no-watch"]
