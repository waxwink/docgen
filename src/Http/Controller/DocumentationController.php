<?php


namespace Pinwork\DocGen\Http\Controller;


use Pinwork\Core\Http\Controllers\Controller;
use Pinwork\DocGen\DocumentationGenerator;

class DocumentationController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function responses()
    {
        $responses = require_once (base_path() . "/resources/lang/en/response.php");
        return view('docgen::responses', compact('responses'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function routes()
    {
        $docGen = app(DocumentationGenerator::class);
        $routes = $docGen->generate();
        return view('docgen::routes', compact('routes'));
    }
}
