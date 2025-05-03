FROM node:18-alpine

WORKDIR /app

COPY . .

RUN chmod +x entrypoint.sh

ENTRYPOINT ["sh", "./entrypoint.sh"]
