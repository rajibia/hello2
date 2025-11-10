<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\Store;
use App\Repositories\StoreRepository;
use Illuminate\Http\Request;

class StoreController extends AppBaseController
{
    /** @var StoreRepository */
    private $storeRepository;

    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * Display a listing of the Store.
     *
     * @return Factory|View
     */
    public function index()
    {
        return view('stores.index');
    }

    /**
     * Store a newly created Store in storage.
     *
     * @param CreateStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(CreateStoreRequest $request)
    {
        $input = $request->all();
        $this->storeRepository->create($input);

        return $this->sendSuccess(__('messages.store.store') . ' ' . __('messages.common.saved_successfully'));
    }

    /**
     * Show the form for editing the specified Store.
     *
     * @param Store $store
     *
     * @return JsonResponse
     */
    public function edit(Store $store)
    {
        return $this->sendResponse($store, 'Store retrieved successfully.');
    }

    /**
     * Update the specified Store in storage.
     *
     * @param Store $store
     * @param UpdateStoreRequest $request
     *
     * @return JsonResponse
     */
    public function update(Store $store, UpdateStoreRequest $request)
    {
        $input = $request->all();
        $this->storeRepository->update($input, $store->id);

        return $this->sendSuccess(__('messages.store.store') . ' ' . __('messages.common.updated_successfully'));
    }

    /**
     * Remove the specified Store from storage.
     *
     * @param Store $store
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function destroy(Store $store)
    {
        try {
            $this->storeRepository->delete($store->id);
            
            return $this->sendSuccess(__('messages.store.store').' '.__('messages.common.deleted_successfully'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.store.store') . ' ' . __('messages.common.cant_be_deleted'),
            ], 404);
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function changeStatus(Request $request)
    {
        $status = Store::findOrFail($request->id);
        $status->status = ! $status->status;
        $status->save();

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }
}
