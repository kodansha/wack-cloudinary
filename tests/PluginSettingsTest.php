<?php

namespace WackCloudinaryTest;

use WP_Mock;
use Mockery;
use WackCloudinary\PluginSettings;
use WackCloudinary\Constants;

final class PluginSettingsTest extends WP_Mock\Tools\TestCase
{
    //==========================================================================
    // getRootFolderFromConstant
    //==========================================================================
    // phpcs:ignore
    public function test_getRootFolderFromConstant_settings_found(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
        ->andReturn([
            'root_folder' => 'test-folder',
        ]);
        $result = PluginSettings::getRootFolderFromConstant();
        $this->assertEquals('test-folder', $result);
    }

    // phpcs:ignore
    public function test_getRootFolderFromConstant_settings_not_found(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
            ->andReturn([]);
        $result = PluginSettings::getRootFolderFromConstant();
        $this->assertNull($result);
    }

    //==========================================================================
    // getTypeFromConstant
    //==========================================================================
    // phpcs:ignore
    public function test_getTypeFromConstant_settings_found_and_valid(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
        ->andReturn([
            'type' => 'authenticated'
        ]);
        $result = PluginSettings::getTypeFromConstant();
        $this->assertEquals('authenticated', $result);
    }

    // phpcs:ignore
    public function test_getTypeFromConstant_settings_not_found(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
            ->andReturn([]);
        $result = PluginSettings::getTypeFromConstant();
        $this->assertEquals('upload', $result);
    }

    // phpcs:ignore
    public function test_getTypeFromConstant_settings_found_but_invalid(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
        ->andReturn([
            'type' => 'invalid'
        ]);
        $result = PluginSettings::getTypeFromConstant();
        $this->assertEquals('upload', $result);
    }

    //==========================================================================
    // getNotificationUrlFromConstant
    //==========================================================================
    // phpcs:ignore
    public function test_getNotificationUrlFromConstant_settings_found(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
        ->andReturn([
            'notification_url' => 'https://example.com'
        ]);
        $result = PluginSettings::getNotificationUrlFromConstant();
        $this->assertEquals('https://example.com', $result);
    }

    // phpcs:ignore
    public function test_getNotificationUrlFromConstant_settings_not_found(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
            ->andReturn([]);
        $result = PluginSettings::getNotificationUrlFromConstant();
        $this->assertNull($result);
    }

    //==========================================================================
    // getBasicAuthFromConstant
    //==========================================================================
    // phpcs:ignore
    public function test_getBasicAuthFromConstant_settings_found_and_valid(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
        ->andReturn([
            'basic_auth' => [
                'username' => 'test-user',
                'password' => 'test-password',
            ]
        ]);
        $result = PluginSettings::getBasicAuthFromConstant();
        $this->assertEquals([
            'username' => 'test-user',
            'password' => 'test-password',
        ], $result);
    }

    // phpcs:ignore
    public function test_getBasicAuthFromConstant_settings_found_but_invalid_1(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
        ->andReturn([
            'basic_auth' => [
                'username' => 'test-user',
            ]
        ]);
        $result = PluginSettings::getBasicAuthFromConstant();
        $this->assertNull($result);
    }

    // phpcs:ignore
    public function test_getBasicAuthFromConstant_settings_found_but_invalid_2(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
        ->andReturn([
            'basic_auth' => [
                'password' => 'test-password',
            ]
        ]);
        $result = PluginSettings::getBasicAuthFromConstant();
        $this->assertNull($result);
    }

    // phpcs:ignore
    public function test_getBasicAuthFromConstant_settings_found_but_invalid_3(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
        ->andReturn(['basic_auth' => 'invalid']);
        $result = PluginSettings::getBasicAuthFromConstant();
        $this->assertNull($result);
    }

    // phpcs:ignore
    public function test_getBasicAuthFromConstant_settings_not_found(): void
    {
        $mock = Mockery::mock('overload:' . Constants::class)->makePartial();
        $mock->shouldReceive('settingsConstant')
        ->andReturn(['basic_auth' => []]);
        $result = PluginSettings::getBasicAuthFromConstant();
        $this->assertNull($result);
    }
}
