<?php

namespace App\Http\Controllers\Api\V1\Documents;

use App\Actions\Api\V1\Documents\DocumentActions;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Documents\DocumentResource;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(protected DocumentActions $actions) {}

    public function index(Request $request)
    {
        return DocumentResource::collection($this->actions->index($request, $request->user()));
    }
}
