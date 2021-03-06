<?php

namespace Tests;

use Livewire\Livewire;
use Illuminate\Support\Facades\View;

class LivewireUsesProperAppAndAssetsPathTest extends TestCase
{
    /** @test */
    public function livewire_js_is_unminified_when_app_is_in_debug_mode()
    {
        config()->set('app.debug', true);

        $this->assertStringContainsString(
            '<script src="/livewire/livewire.js?',
            Livewire::assets()
        );

        $this->assertStringContainsString(
            "window.livewire_app_url = '';",
            Livewire::assets()
        );
    }

    /** @test */
    public function livewire_js_calls_reference_relative_root()
    {
        $this->assertStringContainsString(
            '<script src="/livewire/livewire.min.js?',
            Livewire::assets()
        );

        $this->assertStringContainsString(
            "window.livewire_app_url = '';",
            Livewire::assets()
        );
    }

    /** @test */
    public function livewire_js_calls_reference_congigured_asset_url()
    {
        $this->assertStringContainsString(
            '<script src="https://foo.com/assets/livewire/livewire.min.js?',
            Livewire::assets(['asset_url' => 'https://foo.com/assets'])
        );

        $this->assertStringContainsString(
            "window.livewire_app_url = 'https://foo.com/assets';",
            Livewire::assets(['asset_url' => 'https://foo.com/assets'])
        );
    }

    /** @test */
    public function asset_url_trailing_slashes_are_trimmed()
    {
        $this->assertStringContainsString(
            '<script src="https://foo.com/assets/livewire/livewire.min.js?',
            Livewire::assets(['asset_url' => 'https://foo.com/assets/'])
        );

        $this->assertStringContainsString(
            "window.livewire_app_url = 'https://foo.com/assets';",
            Livewire::assets(['asset_url' => 'https://foo.com/assets/'])
        );
    }

    /** @test */
    public function asset_url_passed_into_blade_assets_directive()
    {
        $output = View::make('assets-directive', [
            'options' => ['asset_url' => 'https://foo.com/assets/'],
        ])->render();

        $this->assertStringContainsString(
            '<script src="https://foo.com/assets/livewire/livewire.min.js?',
            $output
        );

        $this->assertStringContainsString(
            "window.livewire_app_url = 'https://foo.com/assets';",
            $output
        );
    }
}
