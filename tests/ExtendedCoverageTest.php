<?php

use Tests\Fixtures\ClassWithCircularRef;
use Tests\Fixtures\ClassWithNullableClosure;
use Tests\Fixtures\ClassWithReadonly;
use Tests\Fixtures\ClassWithSerializeAndNestedClosures;
use Tests\Fixtures\ClassWithSerializeChild;
use Tests\Fixtures\ClassWithUninitializedClosure;
use Tests\Fixtures\ClassWithUnionClosure;

/*
|--------------------------------------------------------------------------
| Queue patterns
|--------------------------------------------------------------------------
*/

test('dispatch closure with no captures', function () {
    $closure = fn () => 'dispatched';

    expect(s($closure)())->toBe('dispatched');
})->with('serializers');

test('dispatch closure capturing object with __serialize', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'podcast',
        [fn () => 'step'],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('podcast');
})->with('serializers');

test('dispatch closure capturing two objects with __serialize', function () {
    $obj1 = new ClassWithSerializeAndNestedClosures(
        'first',
        [fn () => 'a'],
        fn () => 'cb'
    );
    $obj2 = new ClassWithSerializeAndNestedClosures(
        'second',
        [fn () => 'b'],
        fn () => 'cb'
    );

    $closure = function () use ($obj1, $obj2) {
        return $obj1->name .'+'. $obj2->name;
    };

    expect(s($closure)())->toBe('first+second');
})->with('serializers');

test('dispatch closure capturing object with closure casters in array', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'caster-model',
        [
            fn ($val) => json_encode($val),
            fn ($val) => strtoupper($val),
        ],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('caster-model');
})->with('serializers');

test('catch callback with no captures', function () {
    $closure = fn () => 'caught';

    expect(s($closure)())->toBe('caught');
})->with('serializers');

test('catch callback capturing object', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'failed-job',
        [fn () => 'step'],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return 'failed: '. $obj->name;
    };

    expect(s($closure)())->toBe('failed: failed-job');
})->with('serializers');

/*
|--------------------------------------------------------------------------
| Bus::chain patterns
|--------------------------------------------------------------------------
*/

test('chain closure step with no captures', function () {
    $closure = fn () => 'step-done';

    expect(s($closure)())->toBe('step-done');
})->with('serializers');

test('chain closure capturing object', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'chain-job',
        [fn () => 'inner'],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return 'processing '. $obj->name;
    };

    expect(s($closure)())->toBe('processing chain-job');
})->with('serializers');

test('multiple closure steps as array', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'multi',
        [
            fn () => 'step1',
            fn () => 'step2',
            fn () => 'step3',
        ],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return array_map(fn ($s) => $s(), $obj->chainItems);
    };

    expect(s($closure)())->toBe(['step1', 'step2', 'step3']);
})->with('serializers');

test('chain catch callback', function () {
    $closure = function () {
        return 'chain-failed';
    };

    expect(s($closure)())->toBe('chain-failed');
})->with('serializers');

/*
|--------------------------------------------------------------------------
| Bus::batch patterns
|--------------------------------------------------------------------------
*/

test('batch then/catch/finally callbacks with no captures', function () {
    $closure = fn () => 'batch-done';

    expect(s($closure))->toBeInstanceOf(Closure::class);
})->with('serializers');

test('pending batch with all callbacks stored in options array', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'batch',
        [
            fn () => 'then',
            fn () => 'catch',
            fn () => 'finally',
        ],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return $obj->chainItems[0]();
    };

    expect(s($closure)())->toBe('then');
})->with('serializers');

/*
|--------------------------------------------------------------------------
| Bus::batch inside Bus::chain (#126 regression)
|--------------------------------------------------------------------------
*/

test('chained batch with closure chain items', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'import',
        [
            fn () => 'process-csv',
            fn () => 'validate',
        ],
        fn () => 'finally-cleanup'
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('import');
})->with('serializers');

test('chain closures callable after round-trip', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'import',
        [
            fn () => 'process-csv',
            fn () => 'validate',
        ],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return array_map(fn ($j) => $j(), $obj->chainItems);
    };

    expect(s($closure)())->toBe(['process-csv', 'validate']);
})->with('serializers');

test('finally callback callable after round-trip', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'import',
        [fn () => 'step'],
        fn () => 'cleanup-done'
    );

    $closure = function () use ($obj) {
        return ($obj->callback)();
    };

    expect(s($closure)())->toBe('cleanup-done');
})->with('serializers');

/*
|--------------------------------------------------------------------------
| Events patterns
|--------------------------------------------------------------------------
*/

test('queueable listener with no captures', function () {
    $closure = fn () => 'event-handled';

    expect(s($closure)())->toBe('event-handled');
})->with('serializers');

test('queueable listener capturing object', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'podcast',
        [fn () => 'step'],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return 'handling '. $obj->name;
    };

    expect(s($closure)())->toBe('handling podcast');
})->with('serializers');

test('queued closure with catch callbacks', function () {
    $catchCb = function () {
        return 'listen-failed';
    };

    expect(s($catchCb)())->toBe('listen-failed');
})->with('serializers');

/*
|--------------------------------------------------------------------------
| Scheduling patterns
|--------------------------------------------------------------------------
*/

test('basic scheduled closure', function () {
    $closure = fn () => 'scheduled';

    expect(s($closure)())->toBe('scheduled');
})->with('serializers');

test('scheduled closure capturing scalar', function () {
    $table = 'recent_users';

    $closure = function () use ($table) {
        return "delete from $table";
    };

    expect(s($closure)())->toBe('delete from recent_users');
})->with('serializers');

/*
|--------------------------------------------------------------------------
| Concurrency patterns
|--------------------------------------------------------------------------
*/

test('concurrency closure with no captures', function () {
    $closure = fn () => 42;

    expect(s($closure)())->toBe(42);
})->with('serializers');

test('concurrency closure capturing object', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'task',
        [fn () => 'result'],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('task');
})->with('serializers');

test('concurrency multiple closures as array', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'parallel',
        [
            fn () => 10,
            fn () => 20,
        ],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return array_sum(array_map(fn ($t) => $t(), $obj->chainItems));
    };

    expect(s($closure)())->toBe(30);
})->with('serializers');

/*
|--------------------------------------------------------------------------
| 3rd party: crunz task scheduler
|--------------------------------------------------------------------------
*/

test('crunz-style task with multiple filter closures', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'daily-report',
        [
            fn () => true,
            fn () => true,
        ],
        fn () => 'run-task'
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('daily-report');
})->with('serializers');

test('crunz-style filters callable after round-trip', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'report',
        [
            fn () => true,
            fn () => false,
        ],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return count(array_filter($obj->chainItems, fn ($f) => $f()));
    };

    expect(s($closure)())->toBe(1);
})->with('serializers');

test('crunz-style before/after callbacks callable', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'report',
        [
            fn () => 'before',
            fn () => 'after',
        ],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return $obj->chainItems[0]() .'+'. $obj->chainItems[1]();
    };

    expect(s($closure)())->toBe('before+after');
})->with('serializers');

/*
|--------------------------------------------------------------------------
| Userland patterns
|--------------------------------------------------------------------------
*/

test('pipeline closure stages in array', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'pipeline',
        [
            fn ($d) => strtoupper($d),
            fn ($d) => trim($d),
        ],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        $result = ' hello ';
        foreach ($obj->chainItems as $stage) {
            $result = $stage($result);
        }

        return $result;
    };

    expect(s($closure)())->toBe('HELLO');
})->with('serializers');

test('middleware closure chain in array', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'middleware',
        [
            fn ($next) => 'auth>'. $next,
            fn ($next) => 'log>'. $next,
        ],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        $result = 'handler';
        foreach (array_reverse($obj->chainItems) as $mw) {
            $result = $mw($result);
        }

        return $result;
    };

    expect(s($closure)())->toBe('auth>log>handler');
})->with('serializers');

test('closure typed property with array closures', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'webhook',
        [
            fn () => 'transform',
            fn () => 'success',
        ],
        fn () => 'verify'
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('webhook');
})->with('serializers');

test('array closure transformers callable after round-trip', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'webhook',
        [
            fn ($d) => strtoupper($d),
            fn ($d) => $d.'!',
        ],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return $obj->chainItems[0]('hello');
    };

    expect(s($closure)())->toBe('HELLO');
})->with('serializers');

test('success and failure callback arrays callable', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'webhook',
        [
            fn () => 'logged',
            fn () => 'alerted',
        ],
        fn () => 'cb'
    );

    $closure = function () use ($obj) {
        return $obj->chainItems[0]() .'+'. $obj->chainItems[1]();
    };

    expect(s($closure)())->toBe('logged+alerted');
})->with('serializers');

/*
|--------------------------------------------------------------------------
| Nested objects
|--------------------------------------------------------------------------
*/

test('batch captures another batch', function () {
    $inner = new ClassWithSerializeAndNestedClosures(
        'inner',
        [fn () => 'inner-step'],
        fn () => 'cb'
    );
    $outer = new ClassWithSerializeAndNestedClosures(
        'outer',
        [fn () => 'outer-step'],
        fn () => 'cb'
    );

    $closure = function () use ($outer, $inner) {
        return $outer->name .'+'. $inner->name;
    };

    expect(s($closure)())->toBe('outer+inner');
})->with('serializers');

test('closure captures model inside batch', function () {
    $batch = new ClassWithSerializeAndNestedClosures(
        'batch',
        [fn () => 'job'],
        fn () => 'cb'
    );

    $closure = function () use ($batch) {
        return $batch->chainItems[0]();
    };

    expect(s($closure)())->toBe('job');
})->with('serializers');

/*
|--------------------------------------------------------------------------
| Type variations
|--------------------------------------------------------------------------
*/

test('nullable closure property with value and array closures', function () {
    $obj = new ClassWithNullableClosure(
        'nullable',
        fn () => 'handled',
        [fn () => 'item']
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('nullable');
})->with('serializers');

test('nullable closure property with null and array closures', function () {
    $obj = new ClassWithNullableClosure(
        'null-handler',
        null,
        [fn () => 'item']
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('null-handler');
})->with('serializers');

test('union closure|string property with closure value', function () {
    $obj = new ClassWithUnionClosure(
        'union-closure',
        fn () => 'action',
        [fn () => 'item']
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('union-closure');
})->with('serializers');

test('union closure|string property with string value', function () {
    $obj = new ClassWithUnionClosure(
        'union-string',
        'SomeClass@handle',
        [fn () => 'item']
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('union-string');
})->with('serializers');

/*
|--------------------------------------------------------------------------
| Structural variations
|--------------------------------------------------------------------------
*/

test('child class inherits parent __serialize with array closures', function () {
    $obj = new ClassWithSerializeChild(
        'parent',
        fn () => 'parent-cb',
        [fn () => 'child-item']
    );

    $closure = function () use ($obj) {
        return $obj->parentName;
    };

    expect(s($closure)())->toBe('parent');
})->with('serializers');

test('readonly property with array closures', function () {
    $obj = new ClassWithReadonly(
        'readonly',
        [fn () => 'read']
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('readonly');
})->with('serializers');

test('circular self-reference with array closures', function () {
    $obj = new ClassWithCircularRef(
        'circular',
        [fn () => 'item']
    );
    $obj->self = $obj;

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('circular');
})->with('serializers');

test('uninitialized closure property with array closures', function () {
    $obj = new ClassWithUninitializedClosure(
        'uninit',
        [fn () => 'item']
    );

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('uninit');
})->with('serializers');
