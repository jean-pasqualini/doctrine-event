db-create:
	vendor/bin/doctrine orm:schema-tool:create

db-update:
	vendor/bin/doctrine orm:schema-tool:update --force --dump-sql

db-reset:
	vendor/bin/doctrine orm:schema-tool:drop --force > /dev/null
	vendor/bin/doctrine orm:schema-tool:create > /dev/null

demo:
	@php src/main.php $(FILTER)
