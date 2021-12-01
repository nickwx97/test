docker build --no-cache -t aas-web-app-img .
docker stop aas-web-app || true
docker run -d --rm -e VIRTUAL_HOST=aas.sitict.net -e VIRTUAL_PORT=80 --name aas-web-app aas-web-app-img
docker container prune --force
docker rmi $(docker images --filter "dangling=true" -q --no-trunc)