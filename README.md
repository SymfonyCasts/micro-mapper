# MicroMapper: The Tiny, Underwhelming Data Mapper

Need to map one object (e.g. a Doctrine entity) to another
object (e.g. a DTO) and love writing the mapping code manually?
Then this library is for you!

```php
$dragon = $dragonRepository->find(1);

// YOU write the mapping code in a "mapper" class (shown later)
$dragonDTO = $microMapper->map($dragon, DragonDTO::class);
```

MicroMapper is similar to other data mappers, like
[jane-php/automapper](https://github.com/janephp/automapper), except less
impressive! Jane's Automapper is awesome and handles a lot of heavy lifting.
With MicroMapper, *you* do the heavy lifting. Let's review with a table!

| Feature                          | MicroMapper | Jane's Automapper |
|----------------------------------|-------------|-------------------|
| Some of the mapping is automatic | ❌           | ✅                 |
| Extensible                       | ✅           | ✅                 |
| Handles nested objects           | ✅           | ✅                 |
| Small & Dead-simple              | ✅           | (not SO simple    |

## Installation

```bash
composer require symfonycasts/micro-mapper
```

Done!

## Usage

Suppose you have a `Dragon` entity, and you want to map it to a
`DragonApi` object (perhaps to use with API Platform, like we do
in our [Api Platform EP3 Tutorial](https://symfonycasts.com/screencast/api-platform3-extending)).

To do this, create a "mapper" class that defines how to map:

TODO
