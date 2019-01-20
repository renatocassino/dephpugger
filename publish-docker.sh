VERSION=$(grep VERSION src/Dephpug/Dephpugger.php | sed -e "s/.*\'\([0-9]*\.[0-9]*\.[0-9]*\).*/\1/g")

docker build -t tacnoman/dephpugger:$VERSION .
docker build -t tacnoman/dephpugger:latest

docker push tacnoman/dephpugger:$VERSION
docker push tacnoman/dephpugger:latest

