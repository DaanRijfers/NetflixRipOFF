FROM node:16

# Set working directory
WORKDIR /app

# Copy the application
COPY . .

# Install dependencies
RUN npm install

# Expose port
EXPOSE 8080

CMD ["npm", "run", "serve"]
