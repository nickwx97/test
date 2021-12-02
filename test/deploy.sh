docker build --no-cache -t webapp-img .
docker stop webapp || true
docker run -d --rm -p 80:80 --name webapp webapp-img
docker container prune --force
docker rmi $(docker images --filter "dangling=true" -q --no-trunc) || true