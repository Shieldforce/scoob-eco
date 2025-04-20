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

# Composer install
# echo -e "${YELLOW}➡️  Instalando as dependências...${NC}"
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html composer/composer \
    composer install --ignore-platform-reqs > /dev/null 2>&1 &

PID=$!
spinner $PID "Instalando pacotes..." # Usando a função spinner com texto dinâmico
wait $PID
RESULT=$?

if [ $RESULT -eq 0 ]; then
    echo -e "${GREEN}✅ Composer install finalizado com sucesso!${NC}"
else
    echo -e "${RED}❌ Erro ao instalar as dependências com o Composer.${NC}"
    exit 1
fi

# Composer require shieldforce/scoob
# echo -e "${YELLOW}➡️  Instalando o Scoob...${NC}"
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html composer/composer \
    composer require shieldforce/scoob > /dev/null 2>&1 &

PID=$!
spinner $PID "Inserindo o pacote do Scoob..." # Usando a função spinner com texto dinâmico
wait $PID
RESULT=$?

if [ $RESULT -eq 0 ]; then
    echo -e "${GREEN}✅ Pacote do Scoob inserido com sucesso!${NC}"
else
    echo -e "${RED}❌ Erro ao inserir pacote do Scoob.${NC}"
    exit 1
fi

bash ./vendor/shieldforce/scoob/scoob --type docker-php-nginx --version 8.4 --port 9000
