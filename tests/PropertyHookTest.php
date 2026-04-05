<?php

it('does not trigger set hook during serialization', function () {
    $order = new OrderWithSetHook();
    $order->price = 50.00;

    // Reset the counter after initial set
    OrderWithSetHook::$setHookCallCount = 0;

    $closure = function () use ($order) {
        return $order->price;
    };

    $serialized = s($closure);

    expect(OrderWithSetHook::$setHookCallCount)->toBe(0)
        ->and($serialized())->toBe(50.00);
})->with('serializers');

it('does not trigger get hook during serialization', function () {
    $product = new ProductWithGetHook('Widget', 25.00);

    // Reset the counter after construction
    ProductWithGetHook::$getHookCallCount = 0;

    $closure = function () use ($product) {
        return $product->name;
    };

    $serialized = s($closure);

    expect(ProductWithGetHook::$getHookCallCount)->toBe(0);

    // After unserialization, accessing the property SHOULD trigger the hook
    $result = $serialized();
    expect($result)->toBe('Widget');
})->with('serializers');

it('does not trigger hooks on object with both get and set hooks', function () {
    $item = new ItemWithBothHooks();
    $item->label = 'test-item';

    ItemWithBothHooks::$getHookCallCount = 0;
    ItemWithBothHooks::$setHookCallCount = 0;

    $closure = function () use ($item) {
        return $item->label;
    };

    $serialized = s($closure);

    expect(ItemWithBothHooks::$getHookCallCount)->toBe(0)
        ->and(ItemWithBothHooks::$setHookCallCount)->toBe(0);

    $result = $serialized();
    expect($result)->toBe('test-item');
})->with('serializers');

it('still serializes objects without hooks correctly', function () {
    $obj = new PlainObjectNoHooks();
    $obj->name = 'plain';
    $obj->value = 42;

    $closure = function () use ($obj) {
        return [$obj->name, $obj->value];
    };

    $serialized = s($closure);

    expect($serialized())->toBe(['plain', 42]);
})->with('serializers');

it('does not trigger side effects during serialization of hooked property', function () {
    SideEffectTracker::$log = [];

    $tracker = new ObjectWithSideEffectHook();
    $tracker->status = 'active';

    // Clear the log from the initial set
    SideEffectTracker::$log = [];

    $closure = function () use ($tracker) {
        return $tracker->status;
    };

    $serialized = s($closure);

    expect(SideEffectTracker::$log)->toBe([])
        ->and($serialized())->toBe('active');
})->with('serializers');

it('handles bound closure with hooked properties', function () {
    $binding = new BoundClosureWithHooks();

    $f = $binding->getClosure();

    OrderWithSetHook::$setHookCallCount = 0;

    $serialized = s($f);

    expect(OrderWithSetHook::$setHookCallCount)->toBe(0);

    $result = $serialized();
    expect($result)->toBe(99.99);
})->with('serializers');

// --- Test fixture classes ---

class OrderWithSetHook
{
    public static int $setHookCallCount = 0;

    public float $price {
        set(float $value) {
            static::$setHookCallCount++;
            $this->price = $value;
        }
    }
}

class ProductWithGetHook
{
    public static int $getHookCallCount = 0;

    public string $name {
        get {
            static::$getHookCallCount++;

            return $this->name;
        }
    }

    public float $price;

    public function __construct(string $name, float $price)
    {
        $this->name = $name;
        $this->price = $price;
    }
}

class ItemWithBothHooks
{
    public static int $getHookCallCount = 0;
    public static int $setHookCallCount = 0;

    public string $label {
        get {
            static::$getHookCallCount++;

            return $this->label;
        }
        set(string $value) {
            static::$setHookCallCount++;
            $this->label = $value;
        }
    }
}

class PlainObjectNoHooks
{
    public string $name;
    public int $value;
}

class SideEffectTracker
{
    public static array $log = [];
}

class ObjectWithSideEffectHook
{
    public string $status {
        set(string $value) {
            SideEffectTracker::$log[] = "status set to: $value";
            $this->status = $value;
        }
    }
}

class BoundClosureWithHooks
{
    private OrderWithSetHook $order;

    public function __construct()
    {
        $this->order = new OrderWithSetHook();
        $this->order->price = 99.99;
    }

    public function getClosure(): Closure
    {
        return function () {
            return $this->order->price;
        };
    }
}
