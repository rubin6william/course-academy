##Course Academy

- This project displays a report with enrolments

### Start the docker container
(This will start the development server on http://localhost)

```bash
./vendor/bin/sail up -d
```

### Run migrations and seed database with test data
```bash
./vendor/bin/sail php artisan migrate --seed
```

### For hot module replacement
```bash
./vendor/bin/sail yarn run hot
```
