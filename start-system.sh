#!/bin/bash

# Cores ANSI
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# ASCII ART
echo -e "\e[33;36m  ----------------------------------------------------------------\e[0m"
echo -e "\e[33;36m |\e[33;33m    ----              ------   ------   -----   -----  |------  \e[0m\e[33;36m|\e[0m"
echo -e "\e[33;36m |\e[33;33m  --------           |        |        |     | |     | |      | \e[0m\e[33;36m|\e[0m"
echo -e "\e[33;36m |\e[33;33m |  -  -  |           ------  |        |     | |     | |------  \e[0m\e[33;36m|\e[0m"
echo -e "\e[33;36m |\e[33;33m     --   (^^^= - - -       | |        |     | |     | |      | \e[0m\e[33;36m|\e[0m"
echo -e "\e[33;36m |\e[33;33m     ||  //           ------   ------   -----   -----  |------  \e[0m\e[33;36m|\e[0m"
echo -e "\e[33;36m |\e[33;33m     || //                                                      \e[0m\e[33;36m|\e[0m"
echo -e "\e[33;36m |\e[33;33m     ||                                                         \e[0m\e[33;36m|\e[0m"
echo -e "\e[33;36m  ---------------------------------------------------------------- \e[0m"
echo -e "${RED}AGUARDE QUE O SCOOB VAI INSTALAR TUDO QUE VOCÊ PRECISA...!${NC}"
echo ""

# Função de spinner
spinner() {
    local pid=$1
    local delay=0.1
    local spinstr='⠋⠙⠹⠸⠼⠴⠦⠧⠇⠏'
    local message=$2 # Texto personalizado

    while kill -0 $pid 2>/dev/null; do
        for i in $(seq 0 ${#spinstr}); do
            printf "\r${YELLOW}${message} %s${NC}" "${spinstr:i:1}"
            sleep $delay
        done
    done
    printf "\r"
}


# Limpando o cache do Composer
./src/Core/exec_spinner.sh \
    "docker run --rm \
         -u "$(id -u):$(id -g)" \
         -v "$(pwd):/var/www/html" \
         -w /var/www/html composer/composer \
         composer clear-cache > /dev/null 2>&1" \
    "Limpando cache do composer..."

# Composer install
./src/Core/exec_spinner.sh \
    "docker run --rm \
         -u "$(id -u):$(id -g)" \
         -v "$(pwd):/var/www/html" \
         -w /var/www/html composer/composer \
         composer install --ignore-platform-reqs > /dev/null 2>&1" \
    "Instalando pacotes..."

# Composer require shieldforce/scoob
./src/Core/exec_spinner.sh \
    "docker run --rm \
         -u "$(id -u):$(id -g)" \
         -v "$(pwd):/var/www/html" \
         -w /var/www/html composer/composer \
         composer require shieldforce/scoob:dev-main --prefer-dist > /dev/null 2>&1" \
    "Inserindo o pacote do Scoob..."

default_port=9000

echo "";
bash ./vendor/shieldforce/scoob/progs/question.sh "
 Qual a porta que você quer setar para o container do nginx?"
read -p " Digite a porta do container Nginx Porta padrão (${default_port}): " port

port=${port:-$default_port}

# Etapa montando container ---
bash ./vendor/shieldforce/scoob/scoob --type docker-php-nginx-auto --version 8.4 --port "$port"