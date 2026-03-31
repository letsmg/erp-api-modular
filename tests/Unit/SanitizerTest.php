<?php

namespace Tests\Unit;

use App\Helpers\SanitizerHelper;
use PHPUnit\Framework\TestCase;

class SanitizerTest extends TestCase
{
    /** @test */
    public function it_sanitizes_basic_data()
    {
        $data = [
            'name' => '<b>Test</b> Name',
            'description' => '<p>Description with <script>alert("xss")</script> tags</p>',
            'email' => 'test@example.com',
        ];

        $sanitized = SanitizerHelper::sanitize($data);

        $this->assertEquals('Test Name', $sanitized['name']);
        $this->assertEquals('Description with  tags', $sanitized['description']);
        $this->assertEquals('test@example.com', $sanitized['email']);
    }

    /** @test */
    public function it_preserves_specified_fields_from_sanitization()
    {
        $data = [
            'meta_title' => '<b>Title</b>',
            'schema_markup' => '<script type="application/ld+json">{"@context": "https://schema.org"}</script>',
            'google_tag_manager' => '<!-- GTM --><script>dataLayer = [];</script>',
        ];

        $sanitized = SanitizerHelper::sanitize($data, ['schema_markup', 'google_tag_manager']);

        $this->assertEquals('Title', $sanitized['meta_title']);
        $this->assertEquals('<script type="application/ld+json">{"@context": "https://schema.org"}</script>', $sanitized['schema_markup']);
        $this->assertEquals('<!-- GTM --><script>dataLayer = [];</script>', $sanitized['google_tag_manager']);
    }

    /** @test */
    public function sanitize_seo_data_preserves_html_fields()
    {
        $seoData = [
            'meta_title' => '<script>alert("xss")</script>Meta Title',
            'meta_description' => '<p>Description with <b>HTML</b></p>',
            'schema_markup' => '<script type="application/ld+json">{"@context": "https://schema.org"}</script>',
            'google_tag_manager' => '<!-- Google Tag Manager --><script>dataLayer = [];</script>',
            'h1' => '<h1>Heading</h1>',
            'text1' => '<em>Text</em> content',
        ];

        $sanitized = SanitizerHelper::sanitizeSeoData($seoData);

        $this->assertEquals('Meta Title', $sanitized['meta_title']);
        $this->assertEquals('Description with HTML', $sanitized['meta_description']);
        $this->assertEquals('Heading', $sanitized['h1']);
        $this->assertEquals('Text content', $sanitized['text1']);
        
        $this->assertEquals('<script type="application/ld+json">{"@context": "https://schema.org"}</script>', $sanitized['schema_markup']);
        $this->assertEquals('<!-- Google Tag Manager --><script>dataLayer = [];</script>', $sanitized['google_tag_manager']);
    }

    /** @test */
    public function it_handles_nested_arrays()
    {
        $data = [
            'product' => [
                'name' => '<b>Product</b> Name',
                'details' => [
                    'description' => '<p>Detail <script>alert("xss")</script> description</p>',
                    'specs' => [
                        'color' => '<em>Red</em>'
                    ]
                ]
            ],
            'category' => 'Normal Category'
        ];

        $sanitized = SanitizerHelper::sanitize($data);

        $this->assertEquals('Product Name', $sanitized['product']['name']);
        $this->assertEquals('Detail  description', $sanitized['product']['details']['description']);
        $this->assertEquals('Red', $sanitized['product']['details']['specs']['color']);
        $this->assertEquals('Normal Category', $sanitized['category']);
    }

    /** @test */
    public function it_handles_null_and_empty_values()
    {
        $data = [
            'name' => '<b>Test</b>',
            'description' => null,
            'empty_field' => '',
            'numeric' => 123,
            'boolean' => true,
        ];

        $sanitized = SanitizerHelper::sanitize($data);

        $this->assertEquals('Test', $sanitized['name']);
        $this->assertNull($sanitized['description']);
        $this->assertEquals('', $sanitized['empty_field']);
        $this->assertEquals(123, $sanitized['numeric']);
        $this->assertTrue($sanitized['boolean']);
    }

    /** @test */
    public function it_prevents_xss_attacks()
    {
        $xssPayloads = [
            '<script>alert("XSS")</script>',
            'javascript:alert("XSS")',
            '<img src="x" onerror="alert(\'XSS\')">',
            '<svg onload="alert(\'XSS\')">',
            '"><script>alert("XSS")</script>',
        ];

        foreach ($xssPayloads as $payload) {
            $data = ['content' => $payload . ' Safe content'];
            $sanitized = SanitizerHelper::sanitize($data);
            
            $this->assertStringNotContainsString('<script>', $sanitized['content']);
            $this->assertStringNotContainsString('javascript:', $sanitized['content']);
            $this->assertStringNotContainsString('onerror', $sanitized['content']);
            $this->assertStringNotContainsString('onload', $sanitized['content']);
            $this->assertStringContainsString('Safe content', $sanitized['content']);
        }
    }
}
