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
    public function load(object $from, string $toClass, array $context): object
    {
        $entity = $from;

        return new DragonDTO($entity->getId());
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

## Support Us & Symfony

Finding this package useful! We're *thrilled* 😍!

A lot of time & effort from the Symfonycasts team & the Symfony community
goes into creating and maintaining these packages. You can support us and
Symfony by grabbing a subscription to [SymfonyCasts](https://symfonycasts.com)!

## Installation

```bash
composer require symfonycasts/micro-mapper
```

If you're using Symfony, you're done! If not, see
[Stand-alone Library Setup](#stand-alone-library-setup).

## Usage

Suppose you have a `Dragon` entity, and you want to map it to a
`DragonApi` object (perhaps to use with API Platform, like we do
in our [Api Platform EP3 Tutorial](https://symfonycasts.com/screencast/api-platform3-extending)).

### Step 1: Create the Mapper Class

To do this, create a "mapper" class that defines how to map:

```php
namespace App\Mapper;

use App\Entity\Dragon;
use App\ApiResource\DragonApi;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;

#[AsMapper(from: Dragon::class, to: DragonApi::class)]
class DragonEntityToApiMapper implements MapperInterface
{
    public function load(object $from, string $toClass, array $context): object
    {
        $entity = $from;
        assert($entity instanceof Dragon); // helps your editor know the type

        return new DragonApi($entity->getId());
    }

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        $entity = $to;
        // helps your editor know the types
        assert($dto instanceof DragonApi);
        assert($entity instanceof Dragon);

        $dto->name = $entity->getName();
        $dto->firePower = $entity->getFirePower();

        return $entity;
    }
}
```

The mapper class has three parts:

1. `#[AsMapper]` attribute: defines the "from" and "to" classes (needed for
   Symfony usage only).
2. `load()` method: creates/loads the "to" object - e.g. load it from the
    database or create it and populate just the identifier.
3. `populate()` method: populates the "to" object with data from the "from"
    object.

### Step 2: Use The MicroMapper Service

To use the mapper, you need to fetch the `MicroMapperInterface` service. For
example, from a controller:

```php
<?php

namespace App\Controller;

use App\Entity\Dragon;
use Symfonycasts\MicroMapper\MicroMapperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DragonController extends AbstractController
{
    #[Route('/dragons/{id}', name: 'api_dragon_get_collection')]
    public function index(Dragon $dragon, MicroMapperInterface $microMapper)
    {
        $dragonApi = $microMapper->map($dragon, DragonApi::class);

        return $this->json($dragonApi);
    }
}
```

## Reverse Transforming

If you want to do the reverse transformation: `DragonApi` to `Dragon`, it's
the same process: create a mapper class and use the `MicroMapperInterface`.

The mapper:

```php
namespace App\Mapper;

use App\Entity\Dragon;
use App\ApiResource\DragonApi;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;

#[AsMapper(from: DragonApi::class, to: Dragon::class)]
class DragonApiToEntityMapper implements MapperInterface
{
    public function __construct(private DragonRepository $dragonRepository)
    {
    }

    public function load(object $from, string $toClass, array $context): object
    {
        $dto = $from;
        assert($dto instanceof DragonApi);

        return $dto->id ? $this->dragonRepository->find($dto->id) : new Dragon();
    }

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        $entity = $to;
        assert($dto instanceof DragonApi);
        assert($entity instanceof Dragon);

        $entity->setName($dto->name);
        $entity->setFirePower($dto->firePower);

        return $entity;
    }
}
```

In this case, the `load()` method fetches the `Dragon` entity from the
database if it has an `id` property.

## Handling Nested Objects

If you have nested objects, you can use the `MicroMapperInterface` to map
those too. For example, suppose the `Dragon` entity has a `treasures` property
that is a `OneToMany` relation to `Treasure` entity. And in `DragonApi`, we have
a `treasures` property that should hold an array of `TreasureApi` objects.

First, create a mapper for the `Treasure` -> `TreasureApi` mapping:

```php
// ...

#[AsMapper(from: Treasure::class, to: TreasureApi::class)]
class TreasureEntityToApiMapper implements MapperInterface
{
    public function load(object $from, string $toClass, array $context): object
    {
        return new TreasureApi($from->getId());
    }

    public function populate(object $from, object $to, array $context): object
    {
        $entity = $from;
        $dto = $to;

        // ... map all the properties

        return $entity;
    }
}
```

Then, in the `DragonEntityToApiMapper`, use the `MicroMapperInterface` to map the
`Treasure` objects to `TreasureApi` objects:

```php
namespace App\Mapper;

// ...
use App\ApiResource\TreasureApi;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: Dragon::class, to: DragonApi::class)]
class DragonEntityToApiMapper implements MapperInterface
{
    public function __construct(private MicroMapperInterface $microMapper)
    {
    }

    // load() is the same

    public function populate(object $from, object $to, array $context): object
    {
        $entity = $from;
        $dto = $to;
        // ... other properties

        $treasuresApis = [];
        foreach ($entity->getTreasures() as $treasure) {
            $treasuresApis[] = $this->microMapper->map($treasure, TreasureApi::class, [
                MicroMapperInterface::MAX_DEPTH => 1,
            ]);
        }
        $dto->treasures = $treasuresApis;

        return $entity;
    }
}
```

That's it! The result will be a `DragonApi` object with a `treasures` property
that holds an array of `TreasureApi` objects.

## MAX_DEPTH & Circular References

Imagine now that `TreasureEntityToApiMapper` *also* maps a `dragon`
property on the `TreasureApi` object:

```php
// ...

#[AsMapper(from: Treasure::class, to: TreasureApi::class)]
class TreasureEntityToApiMapper implements MapperInterface
{
    public function __construct(private MicroMapperInterface $microMapper)
    {
    }

    // load()

    public function populate(object $from, object $to, array $context): object
    {
        $entity = $from;
        $dto = $to;
        // ... map all the properties
        
        $dto->dragon = $this->microMapper->map($entity->getDragon(), DragonApi::class, [
            MicroMapperInterface::MAX_DEPTH => 1,
        ]);

        return $entity;
    }
}
```

This creates a circular reference: `DragonApi` has a `treasures` property
that we map to an array of `TreasureApi` objects. And each `TreasureApi`
object has a `dragon` property that we map to a `DragonApi` object.
If we're not careful, the micro mapper will go into an infinite loop
and become self-aware.

Thankfully, the `MAX_DEPTH` option tells MicroMapper how many levels deep to
go when mapping, and you *usually* want to set this to 0 or 1 when mapping a
relation.

When the max depth is hit, the `load()` method will be called on the mapper
for that level but `populate()` will *not* be called. This results in a
"shallow" mapping of the object.

To understand this, let's look at a few depth examples:

* `MAX_DEPTH = 0`: Because the depth is immediately hit, the `Dragon` object
  will be mapped to a `DragonApi` object by calling the `load()` method on
  `DragonEntityToApiMapper`. But the `populate()` method will *not* be called.
  This means that each `DragonApi` object will have an `id` but no other data.

* `MAX_DEPTH = 1`: The `Dragon` object will be *fully* mapped to a
  `DragonApi` object: both the `load()` and `populate()` methods will be
  called on its mapper. However, when `DragonEntityToApiMapper` calls the
  `MicroMapperInterface` service to map each `Treasure` object to a
  `TreasureApi` object, that mapping will be *shallow* - e.g. the `TreasureApi`
  object will have an `id` property but no other data (because the max depth
  was hit and so only `load()` is called on `TreasureEntityToApiMapper`).
  
In something like API Platform, you can also use `MAX_DEPTH` to limit the
depth of the serialization for performance. For example, if the `TreasureApi`
object has a `dragon` property that is expressed as the IRI string (e.g.
`/api/dragons/1`), then setting `MAX_DEPTH` to `0` is enough and prevents
extra mapping work.

## Settable Collection Relations on Entities

In our example, the `Dragon` entity has a `treasures` property that is a
`OneToMany` relation to the `Treasure` entity. Our DTO classes have
the same relation: `DragonApi` holds an array of `TreasureApi` objects.

If you want to map a `DragonApi` object to the `Dragon` entity and
the `DragonApi.treasures` property may have changed, you need to
to do this carefully. For example, this will not save correctly:

```php

// ...
use App\ApiResource\TreasureApi;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: DragonApi::class, to: Dragon::class)]
class DragonApiToEntityMapper implements MapperInterface
{
    // ...

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        $entity = $to;
        // ...

        $treasureEntities = new ArrayCollection();
        foreach ($dto->treasures as $treasureApi) {
            $treasureEntities[] = $this->microMapper->map($treasureApi, Treasure::class, [
                // depth=0 because we really just need to load/query each Treasure
                MicroMapperInterface::MAX_DEPTH => 0,
            ]);
        }

        // !!!!! THIS WILL NOT WORK !!!!!
        $entity->setTreasures($treasureEntities);

        return $entity;
    }
}
```

The problem is with the `$entity->setTreasures()` call. In fact, this method probably
doesn't even exist on the `Dragon` entity! Instead you have `addTreasure()` and
`removeTreasure()` methods and *these* must be called instead so that the "owning"
side of the Doctrine relationship is correctly set (otherwise the changes won't save).

An easy way to do this is with the `PropertyAccessorInterface` service:

```php

// ...
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

#[AsMapper(from: DragonApi::class, to: Dragon::class)]
class DragonApiToEntityMapper implements MapperInterface
{
    public function __construct(
        private MicroMapperInterface $microMapper,
        private PropertyAccessorInterface $propertyAccessor
    )
    {
    }

    // ...

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        $entity = $to;
        // ...

        $treasureEntities = [];
        foreach ($dto->treasures as $treasureApi) {
            $treasureEntities[] = $this->microMapper->map($treasureApi, Treasure::class, [
                MicroMapperInterface::MAX_DEPTH => 0,
            ]);
        }

        // this will call the addTreasure() and removeTreasure() methods
        $this->propertyAccessor->setValue($entity, 'treasures', $treasureEntities);

        return $entity;
    }
}
```

## Stand-alone Library Setup

If you're not using Symfony, you can still use MicroMapper! You'll need to
instantiate the `MicroMapper` class and pass it all of your mappings:

```php
$microMapper = new MicroMapper([]);
$microMapper->addMapperConfig(new MapperConfig(
    from: Dragon::class,
    to: DragonApi::class,
    fn() => new DragonEntityToApiMapper($microMapper)
));
$microMapper->addMapperConfig(new MapperConfig(
    from: DragonApi::class,
    to: Dragon::class,
    fn() => new DragonApiToEntityMapper($microMapper)
));

// now it's ready to use!
```

In this case, the `#[AsMapper]` attribute is not needed.

## Credits

- [Ryan Weaver](https://github.com/weaverryan)
- [All Contributors](../../contributors)

## License

MIT License (MIT): see the [License File](LICENSE.md) for more details.
