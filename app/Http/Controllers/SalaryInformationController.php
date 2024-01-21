<?php

namespace App\Http\Controllers;

use App\Models\SalaryInformation;
use App\Http\Requests\StoreSalaryInformationRequest;
use App\Http\Requests\UpdateSalaryInformationRequest;

class SalaryInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalaryInformationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryInformation $salaryInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryInformation $salaryInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalaryInformationRequest $request, SalaryInformation $salaryInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryInformation $salaryInformation)
    {
        //
    }
}
