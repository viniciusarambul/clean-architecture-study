test:
	- docker-compose run --rm app composer run tests
	
run:
	- curl -X POST -d '{"amount":100, "payee":1, "payer": 2}' http://localhost/transaction