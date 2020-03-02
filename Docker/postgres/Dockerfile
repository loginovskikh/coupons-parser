FROM postgres:11.2-alpine

CMD psql -v ON_ERROR_STOP=1 -U "$POSTGRES_USER" -d notebook-symfony -c 'CREATE EXTENSION IF NOT EXISTS "uuid-ossp";'

