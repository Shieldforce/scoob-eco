# Scoob

## Requisitos obrigatórios:
- Docker

#### Atualizações de Segurança:
- Atualização de segurança para containers em produção, Agora os containers das versões docker-php-nginx
  nas versões de php (8.2, 8.3 e 8.4), não executam o container como root, mas como um usuário local, com privilégios limitados, 
  que impedirá escala de privilégios para o host.

---
#### Os containers habilitados para rodar em produção (rodam como usuário limitado):

- scoob --type docker-php-nginx --version 8.2 --port {port}
- scoob --type docker-php-nginx --version 8.3 --port {port}
- scoob --type docker-php-nginx --version 8.4 --port {port}

---
## Instalando Scoob globalmente:
```
cd ~/ && git clone https://github.com/Shieldforce/scoob.git && cd scoob
```
### Crie esse alias no seu ~/.bashrc ou ~/.zshrc e rode os source ~/.bashrc ou ~/.zshrc
```
alias scoob='bash $HOME/scoob/scoob'
```
### Criando alias para rodar o scoob em qualquer projeto:
```
echo "alias scoob='bash $HOME/scoob/scoob'" >> ~/.zshrc; source ~/.zshrc
```
---

---
## Rodando composer install e ignorando qualquer conflito de versão!:
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html composer/composer \
     composer install --ignore-platform-reqs
```
## Instalando Scoob localmente:
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html composer/composer \
     composer require shieldforce/scoob
```
### Comando para rodar local:
```
bash ./vendor/shieldforce/scoob/scoob {comandos scoob}
```
### Exemplo (Vai criar um container com php8.3 na porta 9000):
```
bash ./vendor/shieldforce/scoob/scoob --type docker-php-nginx --version 8.3 --port 9000
```
---

---

### Rede que o scoob é criado scoob-network

### Use scoob ou 'bash ./vendor/shieldforce/scoob/scoob' vai depender de como vc instalou!:

### Com o scoob instalado global ou localmente vc pode usar o composer dele:
```
bash ./vendor/shieldforce/scoob/scoob --composer 'composer update' {container-name}
```

## Tipos de implementação:

### Remover totalmente um container:
```
scoob --type docker-remove {container-name}
```

### Implementar um container PHP Puro:
```
scoob --type docker-php-nginx {parametros obrigatórios}
```

### Implementar um container Laravel:
```
scoob --type docker-laravel {parametros obrigatórios}
```

### Limpar tudo que não está sendo usado no docker (CUIDADO, IRÁ EXCLUIR TUDO QUE NÃO ESTÁ SENDO USADO!):
```
scoob --docker-prune
```
---
### Roda um servidor mysql na rede scoob-network (se rodar: scoob --mysql-ext ele roda com os parâmetros defaults):
### Usuário root tem todos os privilégios! E utiliza a mesma senha --pass='password'
#### Dados Default:
- port: 3399
- user: scoob_user
- pass: scoob_pass
- db: scoob_db
- container: scoob-mysql

```
scoob --mysql-ext=true \
    --port='port_number' \
    --user='user_name' \
    --pass='password' \
    --db='db_name' \
    --container='container-name'
```
---

---
### Roda um servidor redis na rede scoob-network (se rodar: scoob --redis-ext ele roda com os parâmetros defaults):
#### Dados Default:
- port: 6379
- pass: @ScoobRedis-dg333445fvcv
- container: scoob-redis
```
scoob --redis-ext=true \
    --container="scoob-redis" \
    --port="6379" \
    --pass="@ScoobRedis-dg333445fvcv"
```
---

## Exemplos de container para php/nginx e laravel:
- --type                   (obrigatório) : Tipo do container para php sempre será (docker-php-nginx)
- --version                (obrigatório) : Versão do PHP Versões disponíveis (7.3, 7.4, 8.1, 8.2, 8.3, 8.4)
- --port                   (obrigatório) : Porta de Exposição do container
- --redis-port             (opcional) : Seta porta do redis!
- --mysql-port             (opcional) : Seta porta do mysql!
---

Este comando vai instalar um container com php/nginx.
```
scoob --type docker-php-nginx --version 8.4 --port 8084
```

#### Listar containers Exemplo:  --type + --port [php-fpm-8.4-8084] :
```
docker ps
CONTAINER ID   IMAGE              COMMAND                  CREATED         STATUS         PORTS                                                       NAMES
f6d5sf6f56f5   php-fpm-8.4-8084   "docker-php-entrypoi…"   1 minutes ago   Up 1 minutes   8073/tcp, 9000/tcp, 0.0.0.0:8084->80/tcp, :::8084->80/tcp   php-fpm-8.4-8084
```
---
Este comando vai instalar um container com laravel/mysql/redis/supervisor.
```
scoob --type docker-laravel --version 8.4 --port 8094 --redis-port 6394 --mysql-port 3394
```
---
## Supervisor  (No caso de containers: --type docker-laravel)

### Acessar bash do container:
```
docker exec -it {container-name} bash
```

### Listar serviços pendurados no supervisor:
```
docker exec -it {container-name} supervisorctl status

A saída tem que ser parecdia com isso:
horizon                          RUNNING   pid 61, uptime 0:00:44
mariadb                          RUNNING   pid 62, uptime 0:00:44
nginx                            RUNNING   pid 63, uptime 0:00:44
php-fpm                          RUNNING   pid 64, uptime 0:00:44
redis                            RUNNING   pid 65, uptime 0:00:44
```
### Resetar serviços pendurados no supervisor:
```
docker exec -it {container-name} supervisorctl restart all
```

### Cada serviço está explicado a baixo caso falhar!

---
### Acessar redis do container:
Se não passou porta, ela será no caso de 
- php7.3: 6373
- php7.4: 6374
- php8.1: 6381
- php8.2: 6382
- php8.3: 6383
- php8.4: 6384

### Se passou --redis-port será o valor passado:

```
docker exec -it {container-name} redis-cli -p {port}
```

### Env Redis:
```
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT={porta_configurada}
```
### Se precisar colocar senha no redis só passar o parâmetro no arquivo de configuração do redis:
### /etc/redis/redis.conf
### requirepass myStrongPassword123!

---
### Acessar mysql do container:
Se não passou porta, ela será no caso de
- php7.3: 3373
- php7.4: 3374
- php8.1: 3381
- php8.2: 3382
- php8.3: 3383
- php8.4: 3384
  
### Se passou --mysql-port será o valor passado:

```
docker exec -it {container-name} mysql
MariaDB [(none)]> create database {db_name};
O resultado tem que ser: (Query OK, 1 row affected (0.000 sec))
```

### Acesso root:
```
docker exec -it {container-name} mysql
MariaDB [(none)]> GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '{senha_desejada}' WITH GRANT OPTION;
MariaDB [(none)]> GRANT ALL PRIVILEGES ON *.* TO 'root'@'%.%.%.%' IDENTIFIED BY 'senha_desejada' WITH GRANT OPTION;
MariaDB [(none)]> GRANT ALL PRIVILEGES ON *.* TO 'root'@'0.0.0.0' IDENTIFIED BY 'senha_desejada' WITH GRANT OPTION;
MariaDB [(none)]> GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY 'senha_desejada' WITH GRANT OPTION;
MariaDB [(none)]> FLUSH PRIVILEGES;
```

### Env Mysql:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT={porta que configurou}
DB_DATABASE={db_name}
DB_USERNAME=root
DB_PASSWORD={senha_desejada}
```

### Rodar Migrate (Se pedir para criar banco cujo o nome está na variável DB_DATABASE, aceite):
```
docker exec -it --user $(id -u):$(id -g) {container-name} php artisan migrate
```

### Rodar Horizon (Se não estiver instalado rode o primeiro comando):
```
docker exec -it --user $(id -u):$(id -g) {container-name} composer require laravel/horizon
docker exec -it --user $(id -u):$(id -g) {container-name} php artisan horizon:install
```
---

### Versões do PHP com suportes para drivers sqlsrv(Sql server):
- 8.2
- 8.3
- 8.4

### Suporte como cliente para Sql Servers:
- SQL Server 2012 (SP4+)	✅ Suportado
- SQL Server 2014	✅ Suportado
- SQL Server 2016	✅ Suportado
- SQL Server 2017	✅ Suportado
- SQL Server 2019	✅ Suportado
- SQL Server 2022	✅ Suportado
- SQL Azure (Azure SQL DB)	✅ Suportado


### Entrar no container para rodar comandos sempre use o usuário local!
```
docker exec -it --user $(id -u):$(id -g) {container-name} bash
```