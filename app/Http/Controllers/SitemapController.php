<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            url('/'),
            url('/about'),
            url('/academics'),
            url('/admissions'),
            url('/contact'),
        ];

        $lastmod = now()->toAtomString();
        $xml = view('sitemap.xml', compact('urls', 'lastmod'))->render();
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}


