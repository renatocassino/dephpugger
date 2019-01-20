VERSION=$(grep VERSION src/Dephpug/Dephpugger.php | sed -e "s/.*\'\([0-9]*\.[0-9]*\.[0-9]*\).*/\1/g")
BASE_IMAGE="tacnoman/dephpugger"

echo "Update composer vendor libs"
composer update

echo "Create .phar file"
php create-phar.php

echo "Building version $BASE_IMAGE:$VERSION"

docker build -t $BASE_IMAGE:$VERSION .
docker build -t $BASE_IMAGE:latest .

echo "Publish image..."
docker push $BASE_IMAGE:$VERSION
docker push $BASE_IMAGE:latest

echo "Done ;D"

