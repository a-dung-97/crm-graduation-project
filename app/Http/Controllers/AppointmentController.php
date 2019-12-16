<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response



     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $participants = $request->participants;
        $appointment = Arr::except($request->all(), ['participants']);
        $appointment['created_by'] = user()->id;
        $appointment = company()->appointments()->create($appointment);
        $appointment->users()->sync($participants['users']);
        $appointment->leads()->sync($participants['leads']);
        $appointment->contacts()->sync($participants['contacts']);
        return created();
    }

    public function getAppointments(Request $request, $type, $id)
    {
        return AppointmentsResource::collection(getModel($type, $id)->appointments()->with('user:id,name')->paginate($request->query('perPage', 10)));
    }

    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        $participants = $request->participants;
        $info = Arr::except($request->all(), ['participants']);
        $info['updated_by'] = user()->id;
        $appointment->update($info);
        $appointment->users()->sync($participants['users']);
        $appointment->leads()->sync($participants['leads']);
        $appointment->contacts()->sync($participants['contacts']);
        return created();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        delete($appointment);
    }
}
