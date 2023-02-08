<?php

use Spatie\Activitylog\Facades\LogBatch;
use Illuminate\Support\Str;

it('generates uuid after start and end batch properely', function (): void {
    LogBatch::startBatch();
    $uuid = LogBatch::getUuid();
    LogBatch::endBatch();

    expect(LogBatch::isopen())->toBeFalse();

    expect($uuid)->toBeString();
});

it('returns null uuid after end batch properely', function (): void {
    LogBatch::startBatch();
    $uuid = LogBatch::getUuid();
    LogBatch::endBatch();

    expect(LogBatch::isopen())->toBeFalse();
    $this->assertNotNull($uuid);
    expect(LogBatch::getUuid())->toBeNull();
});

it('generates a new uuid after starting new batch properly', function (): void {
    LogBatch::startBatch();
    $firstBatchUuid = LogBatch::getUuid();
    LogBatch::endBatch();

    LogBatch::startBatch();

    LogBatch::startBatch();
    $secondBatchUuid = LogBatch::getUuid();
    LogBatch::endBatch();

    expect(LogBatch::isopen())->toBeTrue();
    $this->assertNotNull($firstBatchUuid);
    $this->assertNotNull($secondBatchUuid);

    $this->assertNotEquals($firstBatchUuid, $secondBatchUuid);
});

it('will not generate new uuid if start already started batch', function (): void {
    LogBatch::startBatch();

    $firstUuid = LogBatch::getUuid();

    LogBatch::startBatch();

    $secondUuid = LogBatch::getUuid();

    LogBatch::endBatch();

    expect(LogBatch::isopen())->toBeTrue();

    expect($secondUuid)->toEqual($firstUuid);
});

it('will not generate uuid if end batch before starting', function (): void {
    LogBatch::endBatch();
    $uuid = LogBatch::getUuid();

    LogBatch::startBatch();

    expect($uuid)->toBeNull();
});

it('can set uuid and start a batch', function (): void {
    $uuid = Str::uuid();

    LogBatch::setBatch($uuid);
    expect(LogBatch::isOpen())->toBeTrue();
    expect(LogBatch::getUuid())->toEqual($uuid);

    LogBatch::endBatch();
    expect(LogBatch::isOpen())->toBeFalse();
});

it('can set uuid for already started batch', function (): void {
    $uuid = Str::uuid();

    LogBatch::startBatch();
    expect(LogBatch::isOpen())->toBeTrue();
    $this->assertNotEquals($uuid, LogBatch::getUuid());

    LogBatch::setBatch($uuid);
    expect(LogBatch::isOpen())->toBeTrue();
    expect(LogBatch::getUuid())->toEqual($uuid);

    LogBatch::endBatch();
    expect(LogBatch::isOpen())->toBeFalse();
});

it('will not return null uuid if end batch that started twice', function (): void {
    LogBatch::startBatch();
    $firstUuid = LogBatch::getUuid();

    LogBatch::startBatch();

    LogBatch::endBatch();

    $notNullUuid = LogBatch::getUuid();

    $this->assertNotNull($firstUuid);
    $this->assertNotNull($notNullUuid);

    expect($notNullUuid)->toBe($firstUuid);
});

it('will return null uuid if end batch that started twice properly', function (): void {
    LogBatch::startBatch();
    $firstUuid = LogBatch::getUuid();

    LogBatch::startBatch();

    LogBatch::endBatch();
    LogBatch::endBatch();

    $nullUuid = LogBatch::getUuid();

    $this->assertNotNull($firstUuid);
    expect($nullUuid)->toBeNull();

    $this->assertNotSame($firstUuid, $nullUuid);
});
