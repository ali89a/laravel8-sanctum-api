<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'category' => CategoryResource::collection(Category::latest()->get())
        ];
        return send_response('Category Retrieved SuccessFul.', $data, Response::HTTP_FOUND);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Api\V1\StoreCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $validated = $request->validated();
            $category = Category::create($validated);
            $data = [
                'category' => $category
            ];
            return response()->successResponse('Category Created SuccessFul.', $data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->errorResponse();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            $data = [
                'category' => new CategoryResource($category)
            ];
            return send_response('Category Retrieved SuccessFul.', $data, Response::HTTP_FOUND);
        }
        return send_error('Category Not Found!', null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Api\V1\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return send_error('Validation Error.', $validator->errors(), Response::HTTP_FOUND);;
        }

        $category->name = $input['name'];
        $category->save();

        return send_response('Category Updated Successfully.', $category, Response::HTTP_FOUND);;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json(['success' => true, 'message' => 'Category deleted successfully.',]);
        }
        return response()->json(['success' => false, 'message' => 'No Category found.',]);
    }
}