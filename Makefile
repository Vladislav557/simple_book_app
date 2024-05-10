create:
	./create-network.sh
	composer create-project symfony/skeleton:"5.*" symfony
	cp ./docker/composer.phar ./symfony

build:
	docker compose -f ./docker-compose.yml up --build -d