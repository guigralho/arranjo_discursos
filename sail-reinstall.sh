docker info > /dev/null 2>&1

# Ensure that Docker is running...
if [ $? -ne 0 ]; then
    echo "Docker is not running."
    exit 1
fi

docker run --rm \
    -v $(pwd):/opt \
    -w /opt \
    composer:latest \
    composer install

LIGHT_CYAN='\033[1;36m'
NC='\033[0m'

echo ""

echo -e "${LIGHT_CYAN}Composer install done${NC}"