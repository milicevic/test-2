//setup project

// copy env for test
cp .env.example .env
// install dependencies
composer install

//bring project app base on compose.yaml / docker setup
sail up -d
// run migrations and seeder...

dummy file for test is dummy_import.csv in public folder and it's match headers with (orders-file2)
