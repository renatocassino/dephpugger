VERSION=$(php version.php)

docker build -t tacnoman/dephpugger:$VERSION .
docker build -t tacnoman/dephpugger:latest

docker push tacnoman/dephpugger:$VERSION
docker push tacnoman/dephpugger:latest

