# Use the official Node.js image
FROM node:20

# Set the working directory
WORKDIR /app

# Copy package.json and package-lock.json
COPY package*.json ./

# Install project dependencies
RUN npm install

# Copy the rest of the application
COPY . .

# Expose the development server port
EXPOSE 8080

# Run Vue development server
CMD ["npm", "run", "serve"]
