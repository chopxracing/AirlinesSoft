<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFlightStatusRequest;
use App\Models\Flight;
use Illuminate\Http\Request;


/**
 * @OA\Get(
 *      path="/api/v1/flights",
 *      summary="Список активных рейсов",
 *      tags={"Flight"},
 *      @OA\Response(
 *          response=200,
 *          description="Ok",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="data", type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="id", type="integer", example=1),
 *                      @OA\Property(property="arrival", type="string", example="Moscow"),
 *                      @OA\Property(property="departure", type="string", example="Sochi"),
 *                      @OA\Property(property="flight_number", type="string", example="AA0001"),
 *                      @OA\Property(property="aircraft_id", type="integer", example=1),
 *                      @OA\Property(property="flight_status_id", type="integer", example=4)
 *
 *                  )
 *              )
 *          )
 *      )
 *  ),
 *
 * @OA\Patch(
 *      path="/api/v1/flights/{flight}",
 *      summary="Обновление статуса полета",
 *      tags={"Flight"},
 *      @OA\Parameter(
 *          name="flight",
 *          in="path",
 *          required=true,
 *          description="ID полета",
 *          @OA\Schema(type="integer"),
 *          example=3
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="flight_status_id", type="integer", example=7)
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Ok",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="data", type="object",
 *                  @OA\Property(property="id", type="integer", example=1),
 *                  @OA\Property(property="flight_status_id", type="integer", example=7)
 *              )
 *          )
 *      )
 *  ),
 *
 */






class ApiController extends Controller
{
    public function update(UpdateFlightStatusRequest $request, Flight $flight)
    {
        $data = $request->validated();
        $flight->update($data);
        return response()->json([
            'data' => $flight
        ]);
    }

    public function index()
    {
        return Flight::where('is_active', 1)->get();
    }

    public function show(Flight $flight)
    {
        return $flight;
    }
}
