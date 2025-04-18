<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreClinicRequest;
use App\Http\Requests\UpdateClinicRequest;
use App\Http\Resources\Admin\ClinicResource;
use App\Models\Clinic;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClinicApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('clinic_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClinicResource(Clinic::with(['managers'])->get());
    }

    public function store(StoreClinicRequest $request)
    {
        $clinic = Clinic::create($request->all());
        $clinic->managers()->sync($request->input('managers', []));

        return (new ClinicResource($clinic))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Clinic $clinic)
    {
        abort_if(Gate::denies('clinic_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ClinicResource($clinic->load(['managers']));
    }

    public function update(UpdateClinicRequest $request, Clinic $clinic)
    {
        $clinic->update($request->all());
        $clinic->managers()->sync($request->input('managers', []));

        return (new ClinicResource($clinic))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Clinic $clinic)
    {
        abort_if(Gate::denies('clinic_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinic->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
