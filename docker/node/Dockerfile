FROM node:22-alpine

WORKDIR /app

COPY node/package*.json ./
RUN npm install

COPY node/ .

EXPOSE 3000

CMD ["npm", "start"]
