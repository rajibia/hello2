<?php

namespace App\Http\Controllers;

use App\Exports\MedicineExport;
use App\Http\Requests\AdjustStockRequest;
use App\Http\Requests\CreateMedicineRequest;
use App\Http\Requests\TransferMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
use App\Models\Medicine;
use App\Models\PurchasedMedicine;
use App\Models\SaleMedicine;
use App\Models\StockAdjustment;
use App\Models\StockTransfer;
use App\Repositories\MedicineRepository;
use Flash;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MedicineController extends AppBaseController
{
    /** @var MedicineRepository */
    private $medicineRepository;

    public function __construct(MedicineRepository $medicineRepo)
    {
        $this->medicineRepository = $medicineRepo;
    }

    public function index()
    {
        return view('medicines.index');
    }
    public function near_expiry()
    {
        return view('medicines.near-expiry');
    }
    public function expired()
    {
        return view('medicines.expired');
    }

    public function create()
    {
        $data = $this->medicineRepository->getSyncList();

        return view('medicines.create')->with($data);
    }

    public function store(CreateMedicineRequest $request)
    {
        $input = $request->all();

        $this->medicineRepository->create($input);

        Flash::success(__('messages.medicine.medicine').' '.__('messages.common.saved_successfully'));

        return redirect(route('medicines.index'));
    }

    public function show(Medicine $medicine)
    {
        $medicine->brand;
        $medicine->category;

        return view('medicines.show')->with('medicine', $medicine);
    }

    public function showMedicine($medicineId)
    {
        $data = $this->medicineRepository->getMedicineAssociatedData($medicineId);
        // dd($data);
        if (! $data) {
            return view('errors.404');
        }
        return view('medicines.show', compact('data'));
       
    }

    public function edit(Medicine $medicine)
    {
        $data = $this->medicineRepository->getSyncList();
        $data['medicine'] = $medicine;

        return view('medicines.edit')->with($data);
    }

    public function transfer(Medicine $medicine)
    {
        // dd($medicine);
        $data = $this->medicineRepository->getSyncList();
        $data['medicine'] = $medicine;

        return view('medicines.transfer')->with($data);
    }

    public function transferSave(Medicine $medicine, TransferMedicineRequest $request)
    {
        $transferFrom = $request->transfer_from;
        $transferTo = $request->transfer_to;
        $transfer_quantity = (int) $request->transfer_quantity;
    
        $stockTransfer = new StockTransfer();
        $stockTransfer->transfer_from = $transferFrom;
        $stockTransfer->transfer_to = $transferTo;
        $stockTransfer->user_id = auth()->user()->id;
        $stockTransfer->medicine_id = $medicine->id;

        if ($transferFrom === 'Dispensary') {
            $medicine->quantity = $medicine->quantity - $transfer_quantity;
            $medicine->available_quantity = $medicine->available_quantity - $transfer_quantity;
            $medicine->store_quantity = $medicine->store_quantity + $transfer_quantity;
        } elseif ($transferFrom === 'Store') {
            $medicine->quantity = $medicine->quantity + $transfer_quantity;
            $medicine->available_quantity = $medicine->available_quantity + $transfer_quantity;
            $medicine->store_quantity = $medicine->store_quantity - $transfer_quantity;
        } 
        $medicine->save();

        
        $stockTransfer->transfer_quantity = $transfer_quantity;
        $stockTransfer->dispensary_balance = $medicine->quantity;
        $stockTransfer->store_balance = $medicine->store_quantity;
        $stockTransfer->save();
    
    
        Flash::success(__('messages.medicine.medicine').' '.__('messages.common.updated_successfully'));

        return redirect(route('medicines.index'));
    }
    public function adjust(Medicine $medicine)
    {
        // dd($medicine);
        $data = $this->medicineRepository->getSyncList();
        $data['medicine'] = $medicine;

        return view('medicines.adjust')->with($data);
    }
    public function adjustSave(Medicine $medicine, AdjustStockRequest $request)
    {

        $stockAdjustment = new StockAdjustment();
        $stockAdjustment->initial_available_quantity = $medicine->available_quantity;
        $stockAdjustment->initial_quantity = $medicine->quantity;
        $stockAdjustment->initial_store_quantity = $medicine->store_quantity;
        $stockAdjustment->user_id = auth()->user()->id;
        $stockAdjustment->medicine_id = $medicine->id;

        $medicine->quantity = $request->available_quantity;
        $medicine->available_quantity = $request->available_quantity;
        $medicine->store_quantity = $request->store_quantity;
        $medicine->save();

        
        $stockAdjustment->current_available_quantity = $medicine->available_quantity;
        $stockAdjustment->current_quantity = $medicine->quantity;
        $stockAdjustment->current_store_quantity = $medicine->store_quantity;
        $stockAdjustment->save();
    
    
        Flash::success(__('messages.medicine.medicine').' '.__('messages.common.updated_successfully'));

        return redirect(route('medicines.index'));
    }

    public function update(Medicine $medicine, UpdateMedicineRequest $request)
    {
        $this->medicineRepository->update($request->all(), $medicine->id);

        Flash::success(__('messages.medicine.medicine').' '.__('messages.common.updated_successfully'));

        return redirect(route('medicines.index'));
    }

    public function destroy(Medicine $medicine)
    {

        if (! canAccessRecord(Medicine::class, $medicine->id)) {
            return $this->sendError(__('messages.flash.medicine_not_found'));
        }

        $purchaseMedicine = PurchasedMedicine::whereMedicineId($medicine->id)->get();
        $saleMedicine = SaleMedicine::whereMedicineId($medicine->id)->get();

        if (isset($purchaseMedicine) && ! empty($purchaseMedicine)) {
            $purchaseMedicine->map->delete();
        }
        if (isset($saleMedicine) && ! empty($saleMedicine)) {
            $saleMedicine->map->delete();
        }

        $this->medicineRepository->delete($medicine->id);

        return $this->sendSuccess(__('messages.medicine.medicine').' '.__('messages.common.deleted_successfully'));
    }

    public function medicineExport()
    {
        return Excel::download(new MedicineExport, 'medicines-'.time().'.xlsx');
    }

    public function showModal(Medicine $medicine)
    {
        $medicine->load(['brand', 'category']);

        $currency = $medicine->currency_symbol ? strtoupper($medicine->currency_symbol) : strtoupper(getCurrentCurrency());
        $medicine = [
            'name' => $medicine->name,
            'brand_name' => $medicine->brand->name,
            'category_name' => $medicine->category->name,
            'salt_composition' => $medicine->salt_composition,
            'side_effects' => $medicine->side_effects,
            'created_at' => $medicine->created_at,
            'selling_price' => checkNumberFormat($medicine->selling_price, $currency),
            'buying_price' => checkNumberFormat($medicine->buying_price, $currency),
            'updated_at' => $medicine->updated_at,
            'description' => $medicine->description,
            'quantity' => $medicine->quantity,
            'available_quantity' => $medicine->available_quantity,
            'store_quantity' => $medicine->store_quantity ?? 0,
        ];

        return $this->sendResponse($medicine, 'Medicine Retrieved Successfully');
    }

    public function checkUseOfMedicine(Medicine $medicine)
    {

        $SaleModel = [
            SaleMedicine::class,
            PurchasedMedicine::class,
        ];

        $result['result'] = canDelete($SaleModel, 'medicine_id', $medicine->id);
        $result['id'] = $medicine->id;

        if ($result) {

            return $this->sendResponse($result, __('This medicine is already used in medicine bills, are you sure want to delete it?'));
        }

        return $this->sendResponse($result, 'Not in use');

    }
    
    /**
     * Get medicine details for AJAX requests
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMedicineDetails()
    {
        $data = $this->medicineRepository->getSyncList();
        $data['medicineCreateUrl'] = route('medicines.store');
        
        return $this->sendResponse($data, 'Medicine details retrieved successfully');
    }
}
