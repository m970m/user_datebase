up:
	docker compose -f Docker/docker-compose.yaml up -d

down:
	docker compose -f Docker/docker-compose.yaml down

db:
	docker exec -it users-db mysql -u root -p

bash:
	docker exec -it users-app bash