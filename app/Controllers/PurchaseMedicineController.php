<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseMedicineExport;
use App\Http\Requests\CreatePurchaseMedicineRequest;
use App\Models\Medicine;
use App\Models\PurchaseMedicine;
use App\Repositories\MedicineRepository;
use App\Repositories\PurchaseMedicineRepository;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseMedicineController extends AppBaseController
{
    /** @var PurchaseMedicineRepository */
    /** @var MedicineRepository */
    private $prchaseMedicineRepository;

    private $medicineRepository;

    public function __construct(PurchaseMedicineRepository $purchaseMedicineRepo, MedicineRepository $medicineRepository)
    {
        $this->prchaseMedicineRepository = $purchaseMedicineRepo;
        $this->medicineRepository = $medicineRepository;
    }

    public function index()
    {

        return view('purchase-medicines.index');

    }

    public function create()
    {

        $data = $this->medicineRepository->getSyncList();
        $medicines = $this->prchaseMedicineRepository->getMedicine();
        $medicineList = $this->prchaseMedicineRepository->getMedicineList();
        $categories = $this->prchaseMedicineRepository->getCategory();
        $categoriesList = $this->prchaseMedicineRepository->getCategoryList();
        $suppliers = $this->prchaseMedicineRepository->getSuppliers();

        return view('purchase-medicines.create', compact('medicines', 'medicineList', 'categories', 'categoriesList', 'suppliers'))->with($data);
    }

    public function payUnpay($id)
    {
        $purchase = PurchaseMedicine::find($id);
        $status = ! $purchase->payment_status;
        $purchase->update(['payment_status' => $status]);
        // dd($purchase);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }
    
    /**
     * Get purchase items for a specific purchase medicine.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItems($id)
    {
        $purchaseMedicine = PurchaseMedicine::with('purchasedMedcines.medicines')->findOrFail($id);
        
        $items = [];
        foreach ($purchaseMedicine->purchasedMedcines as $item) {
            $items[] = [
                'medicine_name' => $item->medicines ? $item->medicines->name : 'Unknown Medicine',
                'lot_no' => $item->lot_no,
                'expiry_date' => $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('d M, Y') : 'N/A',
                'quantity' => $item->quantity,
                'amount' => $item->amount,
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    public function store(CreatePurchaseMedicineRequest $request)
    {

        $input = $request->all();
        $this->prchaseMedicineRepository->store($input);
        flash::success(__('messages.purchase_medicine.medicine_purchased_successfully'));

        return redirect(route('medicine-purchase.index'));
    }

    public function show(PurchaseMedicine $medicinePurchase)
    {
        $medicinePurchase->load(['purchasedMedcines.medicines']);

        return view('purchase-medicines.show', compact('medicinePurchase'));
    }

    public function getMedicine(Medicine $medicine)
    {
        return $this->sendResponse($medicine, 'retrieved');
    }

    public function purchaseMedicineExport()
    {

        $response = Excel::download(new PurchaseMedicineExport, 'purchase-medicine-'.time().'.xlsx');

        ob_end_clean();

        return $response;

    }

    public function usedMedicine()
    {

        return view('used-medicine.index');
    }

    public function destroy(PurchaseMedicine $medicinePurchase)
    {
        $medicinePurchase->delete();

        return $this->sendSuccess(__('messages.flash.medicine_deleted'));
    }
}
