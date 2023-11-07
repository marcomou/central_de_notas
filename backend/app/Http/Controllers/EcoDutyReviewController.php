<?php

namespace App\Http\Controllers;

use App\Http\Requests\EcoDutyReviewRequest;
use App\Http\Resources\EcoDutyReviewResource;
use App\Models\EcoDutyReview;
use Illuminate\Http\Request;

class EcoDutyReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ecoDutyReviews = EcoDutyReview::with([
            'ecoDuty',
            'reviewer'
        ])->paginate();

        return EcoDutyReviewResource::collection($ecoDutyReviews);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\EcoDutyReviewRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EcoDutyReviewRequest $request)
    {
        $ecoDutyReview = EcoDutyReview::create($request->validated());

        return new EcoDutyReviewResource($ecoDutyReview);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EcoDutyReview  $ecoDutyReview
     * @return \Illuminate\Http\Response
     */
    public function show(EcoDutyReview $ecoDutyReview)
    {
        $ecoDutyReview->load([
            'ecoDuty',
            'reviewer'
        ]);

        return new EcoDutyReviewResource($ecoDutyReview);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request\EcoDutyReviewRequest  $request
     * @param  \App\Models\EcoDutyReview  $ecoDutyReview
     * @return \Illuminate\Http\Response
     */
    public function update(EcoDutyReviewRequest $request, EcoDutyReview $ecoDutyReview)
    {
        $ecoDutyReview->update($request->validated());

        return new EcoDutyReviewResource($ecoDutyReview);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EcoDutyReview  $ecoDutyReview
     * @return \Illuminate\Http\Response
     */
    public function destroy(EcoDutyReview $ecoDutyReview)
    {
        $ecoDutyReview->delete();

        return response()->noContent();
    }
}
