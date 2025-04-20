#!/bin/bash

# Caminho base
path_dir=$(dirname "$0")

# Verifica se a flag --mysql-ext foi passada e já armazena o valor
mysql_ext=""

for arg in "$@"; do
  case $arg in
    --mysql-ext=*)
      mysql_ext="${arg#*=}"
      ;;
  esac
done

# Validação obrigatória da flag --mysql-ext (logo no início)
if [[ -z "$mysql_ext" ]]; then
  bash "${path_dir}/error.sh" "Você precisa passar a flag obrigatória --mysql-ext=alguma_coisa"
  exit 1
fi

# Valores padrão
mysql_port=3399
mysql_root_pass="scoob_pass"
mysql_user="scoob_user"
mysql_pass="scoob_pass"
mysql_db="scoob_db"
mysql_container="scoob-mysql"
mysql_network="scoob-network"
mysql_version="latest"
init_sql_path="${path_dir}/ini.sql"

# Parsing dos demais parâmetros
for arg in "$@"; do
  case $arg in
    --port=*)
      mysql_port="${arg#*=}"
      ;;
    --root-pass=*)
      mysql_root_pass="${arg#*=}"
      ;;
    --user=*)
      mysql_user="${arg#*=}"
      ;;
    --pass=*)
      mysql_pass="${arg#*=}"
      ;;
    --db=*)
      mysql_db="${arg#*=}"
      ;;
    --container=*)
      mysql_container="${arg#*=}"
      ;;
    --network=*)
      mysql_network="${arg#*=}"
      ;;
    --version=*)
      mysql_version="${arg#*=}"
      ;;
    --init-sql=*)
      init_sql_path="${arg#*=}"
      ;;
  esac
done

# Verifica se a rede já existe
if ! docker network ls | grep -q "$mysql_network"; then
  echo "Criando rede $mysql_network..."
  docker network create "$mysql_network"
else
  echo "Rede $mysql_network já existe."
fi

# Remove container se já existe
if docker ps -a --format '{{.Names}}' | grep -wq "$mysql_container"; then
  echo "Removendo container existente: $mysql_container..."
  docker rm -f "$mysql_container"
fi

# Gera o conteúdo do arquivo SQL
cat > "$init_sql_path" <<EOF
-- User root

CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '${db_pass}';
GRANT ALL PRIVILEGES ON *.* TO 'scoob_user'@'%' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'root'@'%.%.%.%' IDENTIFIED BY '${db_pass}';
GRANT ALL PRIVILEGES ON *.* TO 'scoob_user'@'%.%.%.%' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'root'@'0.0.0.0' IDENTIFIED BY '${db_pass}';
GRANT ALL PRIVILEGES ON *.* TO 'scoob_user'@'0.0.0.0' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'root'@'localhost' IDENTIFIED BY '${db_pass}';
GRANT ALL PRIVILEGES ON *.* TO 'scoob_user'@'localhost' WITH GRANT OPTION;

-- User default

CREATE USER IF NOT EXISTS 'scoob_user'@'%' IDENTIFIED BY '${db_pass}';
GRANT ALL PRIVILEGES ON scoob_db.* TO 'scoob_user'@'%' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'scoob_user'@'%.%.%.%' IDENTIFIED BY '${db_pass}';
GRANT ALL PRIVILEGES ON scoob_db.* TO 'scoob_user'@'%.%.%.%' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'scoob_user'@'0.0.0.0' IDENTIFIED BY '${db_pass}';
GRANT ALL PRIVILEGES ON scoob_db.* TO 'scoob_user'@'0.0.0.0' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'scoob_user'@'localhost' IDENTIFIED BY '${db_pass}';
GRANT ALL PRIVILEGES ON scoob_db.* TO 'scoob_user'@'localhost' WITH GRANT OPTION;

FLUSH PRIVILEGES;
EOF

# Sobe o container MySQL
docker run -d --rm \
  --name "$mysql_container" \
  -p "$mysql_port":3306 \
  -e MYSQL_ROOT_PASSWORD="$mysql_root_pass" \
  -e MYSQL_USER="$mysql_user" \
  -e MYSQL_PASSWORD="$mysql_pass" \
  -e MYSQL_DATABASE="$mysql_db" \
  --network "$mysql_network" \
  -v "$init_sql_path":/docker-entrypoint-initdb.d/init.sql \
  -v "${mysql_container}_data":/var/lib/mysql \
  mysql:"$mysql_version"

echo ''
bash "${path_dir}/success.sh" "MySQL '$mysql_container' upado com sucesso na porta $mysql_port!"
