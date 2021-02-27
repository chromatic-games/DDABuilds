coming soon

## Development

### Setup project

Copy `.env.example` to `.env`, configure `DB_*`, `APP_URL` and `STEAM_API_KEY`.

```shell
composer install
php artisan key:generate

# required devDependencies
npm install
npm run build
```

### Update project

```shell
# start maintenance
php artisan down --secret=SECRET

# migrate database
php artisan migrate

# required devDependencies
npm install
npm run build

# stop maintenance
php artisan up
```

### Run tests

#### Unit Tests

```shell
composer test
```

#### Browser Tests

⚠ Required `npm run dev-hot` and `php artisan serve`.

```shell
composer test:browser
```

### Code checker

#### ESLint

Run the ESLint checker with `npm run eslint`.