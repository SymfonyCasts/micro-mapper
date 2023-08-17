# MicroMapper: The Tiny, Underwhelming Data Mapper

Need to map one object (e.g. a Doctrine entity) to another
object (e.g. a DTO) and love writing the mapping code manually?
Then this library is for you!

Define a "mapper" class:

```php
use App\Entity\Dragon;
use App\DTO\DragonDTO;

#[AsMapper(from: Dragon::class, to: DragonDTO::class)]
class DragonEntityToDtoMapper implements MapperInterface
{
    public function init(object $from, string $toClass, array $context): object
    {
        $entity = $from;
        $dto = new DragonDTO();
        $dto->id = $entity->getId();
        
        return $dto;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        $entity = $to;

        $dto->name = $entity->getName();
        $dto->firePower = $entity->getFirePower();

        return $entity;
    }
}
```

Then... map!

```php
$dragon = $dragonRepository->find(1);
$dragonDTO = $microMapper->map($dragon, DragonDTO::class);
```

MicroMapper is similar to other data mappers, like
[jane-php/automapper](https://github.com/janephp/automapper), except... less
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

- property accessor for doctrine relations
- nested objects
- manual setup
