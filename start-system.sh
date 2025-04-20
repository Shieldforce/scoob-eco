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

sanitize_name_dash() {
  local input="$1"

  # Converte para minúsculas
  input=$(echo "$input" | tr '[:upper:]' '[:lower:]')

  # Remove acentos
  input=$(echo "$input" | iconv -f utf8 -t ascii//TRANSLIT)

  # Substitui espaços por traço
  input=$(echo "$input" | sed 's/ /-/g')

  # Remove caracteres especiais (mantém letras, números e hífen)
  input=$(echo "$input" | sed 's/[^a-z0-9\-]//g')

  echo "$input"
}

sanitize_name_underscore() {
  local input="$1"

  # Converte para minúsculas
  input=$(echo "$input" | tr '[:upper:]' '[:lower:]')

  # Remove acentos
  input=$(echo "$input" | iconv -f utf8 -t ascii//TRANSLIT)

  # Substitui espaços por underline
  input=$(echo "$input" | sed 's/ /_/g')

  # Substitui hífens por underline
  input=$(echo "$input" | sed 's/-/_/g')

  # Remove caracteres especiais (mantém letras, números e underline)
  input=$(echo "$input" | sed 's/[^a-z0-9_]//g')

  echo "$input"
}

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

#-------------------------------------------------------------------------------------------------------

# Limpando o cache do Composer
./src/Core/exec_spinner.sh \
    "docker run --rm \
         -u "$(id -u):$(id -g)" \
         -v "$(pwd):/var/www/html" \
         -w /var/www/html composer/composer \
         composer clear-cache > /dev/null 2>&1" \
    "Limpando cache do composer..."

#-------------------------------------------------------------------------------------------------------

# Composer install
./src/Core/exec_spinner.sh \
    "docker run --rm \
         -u "$(id -u):$(id -g)" \
         -v "$(pwd):/var/www/html" \
         -w /var/www/html composer/composer \
         composer install --ignore-platform-reqs > /dev/null 2>&1" \
    "Instalando pacotes..."

#-------------------------------------------------------------------------------------------------------

# Composer require shieldforce/scoob
./src/Core/exec_spinner.sh \
    "docker run --rm \
         -u "$(id -u):$(id -g)" \
         -v "$(pwd):/var/www/html" \
         -w /var/www/html composer/composer \
         composer require shieldforce/scoob:dev-main --prefer-dist > /dev/null 2>&1" \
    "Inserindo o pacote do Scoob..."

#-------------------------------------------------------------------------------------------------------

# Composer require vlucas/phpdotenv
./src/Core/exec_spinner.sh \
    "docker run --rm \
         -u "$(id -u):$(id -g)" \
         -v "$(pwd):/var/www/html" \
         -w /var/www/html composer/composer \
         composer require vlucas/phpdotenv > /dev/null 2>&1" \
    "Inserindo o pacote do vlucas/phpdotenv..."

#-------------------------------------------------------------------------------------------------------

# Etapa montando container ---
default_port_nginx=9000
echo "";
bash ./vendor/shieldforce/scoob/progs/question.sh "
 Qual a porta que você quer setar para o container do nginx?"
read -p " Digite a porta do container Nginx (Porta padrão ${default_port_nginx}): " port_nginx
port_nginx=${port_nginx:-$default_port_nginx}
bash ./vendor/shieldforce/scoob/scoob --type docker-php-nginx-auto --version 8.4 --port "$port_nginx"

#-------------------------------------------------------------------------------------------------------
# Etapa montando mysql ---
default_port_mysql=3399
default_user_name=scoob_user
default_password=scoob_pass
default_db_name=scoob_db
default_container_name=scoob-mysql
echo "-";
echo "-";
echo "-";
bash ./vendor/shieldforce/scoob/progs/question.sh "
 Você deseja instalar mysql?"
read -p " S/N: (Por padrão é N): " install_mysql

if [[ "$install_mysql" = "s" ]] || [[ "$install_mysql" = "S" ]]; then

    read -p " Porta do mysql (Por padrão é ${default_port_mysql}): " port_mysql
    echo " -"
    read -p " Usuário do mysql (Por padrão é ${default_user_name}): " user_name
    echo " -"
    read -s -p "Senha do MySQL (Por padrão é ${default_password}): " password
    echo ""
    echo " -"
    read -p " Nome do banco do mysql (Por padrão é ${default_db_name}): " db_name
    echo " -"
    read -p " Nome do container do mysql (Por padrão é ${default_container_name}): " container_name
    echo " -"

    port_mysql=${port_mysql:-$default_port_mysql}
    user_name=$(sanitize_name_underscore "${user_name:-$default_user_name}")
    password=$(sanitize_name_underscore "${password:-$default_password}")
    db_name=$(sanitize_name_underscore "${db_name:-$default_db_name}")
    container_name=$(sanitize_name_underscore "${container_name:-$default_container_name}")

    # Etapa montando container ---
    bash ./vendor/shieldforce/scoob/scoob --mysql-ext=true \
        --port="${port_mysql}" \
        --user="${user_name}" \
        --pass="${password}" \
        --db="${db_name}" \
        --container="${container_name}"
fi