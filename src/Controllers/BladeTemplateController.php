<?php

namespace SkyWebDev\DbMail\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use SkyWebDev\DbMail\BladeTemplate;
use SkyWebDev\DbMail\Requests\BladeTemplateCreateRequest;
use SkyWebDev\DbMail\Requests\BladeTemplateUpdateRequest;

class BladeTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(BladeTemplate::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BladeTemplateCreateRequest $request
     * @return JsonResponse
     */
    public function store(BladeTemplateCreateRequest $request): JsonResponse
    {
        $bladeTemplate = BladeTemplate::query()
            ->create([
                'class_name' => $request->class_name,
                'template_name' => $request->template_name,
                'body' => $request->body,
                'subject' => $request->subject,
            ]);
        return response()->json($bladeTemplate, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param BladeTemplate $bladeTemplate
     * @return JsonResponse
     */
    public function show(BladeTemplate $bladeTemplate): \Illuminate\Http\JsonResponse
    {
        return response()->json($bladeTemplate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BladeTemplateUpdateRequest $request
     * @param BladeTemplate $bladeTemplate
     * @return JsonResponse
     */
    public function update(BladeTemplateUpdateRequest $request, BladeTemplate $bladeTemplate): JsonResponse
    {
        $bladeTemplate->update($request->validated());
        return response()->json($bladeTemplate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BladeTemplate $bladeTemplate
     * @return JsonResponse
     */
    public function destroy(BladeTemplate $bladeTemplate): JsonResponse
    {
        if (file_exists($bladeTemplate->template_path)) {
            $body = file_get_contents($bladeTemplate->template_path);
            $bladeTemplate->update(['body' => $body, 'subject' => '']);
            Artisan::call('view:clear');
        }
        return response()->json(status: 204);
    }
}
