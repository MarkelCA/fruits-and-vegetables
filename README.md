# 🍎🥕 Fruits and Vegetables

## 🎯 Goal
We want to build a service which will take a `request.json` and:
* Process the file and create two separate collections for `Fruits` and `Vegetables`
* Each collection has methods like `add()`, `remove()`, `list()`;
* Units have to be stored as grams;
* Store the collections in a storage engine of your choice. (e.g. Database, In-memory)
* Provide an API endpoint to query the collections. As a bonus, this endpoint can accept filters to be applied to the returning collection.
* Provide another API endpoint to add new items to the collections (i.e., your storage engine).
* As a bonus you might:
  * consider giving option to decide which units are returned (kilograms/grams);
  * how to implement `search()` method collections;
  * use latest version of Symfony's to embbed your logic 

### ✔️ How can I check if my code is working?
You have two ways of moving on:
* You call the Service from PHPUnit test like it's done in dummy test (just run `bin/phpunit` from the console)

or

* You create a Controller which will be calling the service with a json payload

#### Using curl

* List all products:
```bash
curl 'http://localhost:8080/v1/products/?type=fruit&order=desc&orderBy=id&unit=kg'
```

* Add a product:
```bash
curl -X POST 'http://localhost:8080/v1/products/' -d '{"id": 1, "name": "Watermelon", "type": "vegetable", "quantity": 1.5, "unit": "kg"}' -H "Content-Type: application/json"
```

## 💡 Hints before you start working on it
* Keep KISS, DRY, YAGNI, SOLID principles in mind
* Timebox your work - we expect that you would spend between 3 and 4 hours.
* Your code should be tested

## When you are finished
* Please upload your code to a public git repository (i.e. GitHub, Gitlab)

## 🐳 Docker image
Optional. Just here if you want to run it isolated.

### ⌨️ Run development server
```bash
docker compose -f docker/compose.yml up -d
# Open http://127.0.0.1:8080 in your browser
```

### Installing dependencies
```bash
docker exec -it fruits-and-vegetables composer install
```

### Migrating the database
Add the schema and basic data to the database:
```bash
# Default database
docker exec -it fruits-and-vegetables ./bin/console doctrine:migrations:migrate
# Test database
docker exec -it fruits-and-vegetables ./bin/console doctrine:migrations:migrate --conn=test --env=test
```

### 🛂 Running tests
```bash
docker exec -it fruits-and-vegetables ./bin/phpunit
```

### Inserting products from `request.json`
```bash
docker exec -it fruits-and-vegetables ./bin/console product:save ./request.json
```

