<?php

namespace App\Http\Controllers;

use App;
use App\Exports\SupplierExport;
use App\Http\Requests\CreateSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\AdvancedPayment;
use App\Models\Appointment;
use App\Models\BedAssign;
use App\Models\Bill;
use App\Models\BirthReport;
use App\Models\DeathReport;
use App\Models\InvestigationReport;
use App\Models\Invoice;
use App\Models\IpdSupplierDepartment;
use App\Models\OperationReport;
use App\Models\Supplier;
use App\Models\SupplierAdmission;
use App\Models\SupplierCase;
use App\Models\Prescription;
use App\Models\Vaccination;
use App\Repositories\AdvancedPaymentRepository;
use App\Repositories\SupplierRepository;
use Flash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends AppBaseController
{
    /** @var SupplierRepository */
    private $supplierRepository;

    public function __construct(SupplierRepository $supplierRepo)
    {
        $this->supplierRepository = $supplierRepo;
    }

    public function index()
    {
        $data['statusArr'] = Supplier::STATUS_ARR;

        return view('suppliers.index', $data);
    }

    public function create()
    {
        $bloodGroup = getBloodGroups();

        return view('suppliers.create', compact('bloodGroup'));
    }

    public function store(CreateSupplierRequest $request)
    {
        $input = $request->all();

        $this->supplierRepository->store($input);
        $this->supplierRepository->createNotification($input);
        Flash::success(__('messages.advanced_payment.supplier').' '.__('messages.common.saved_successfully'));

        return redirect(route('suppliers.index'));
    }

    public function show($supplierId)
    {
        
        $data = $this->supplierRepository->getSupplierAssociatedData($supplierId);
        // dd($data);
        if (! $data) {
            return view('errors.404');
        }
        return view('suppliers.show', compact('data'));
       
    }
    
    /**
     * Display the supplier ledger.
     *
     * @param  int  $supplierId
     * @return \Illuminate\View\View
     */
    public function ledger($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        
        // Get all purchase medicines for this supplier
        $purchases = $supplier->purchasemedicines()
            ->with('purchasedMedcines.medicines')
            ->get();
            
        // Calculate totals
        $totalPurchases = $purchases->sum('net_amount');
        $totalPaid = $purchases->sum('paid_amount');
        $totalDue = $purchases->sum('balance');
        
        return view('suppliers.ledger', compact(
            'supplier', 
            'purchases', 
            'totalPurchases', 
            'totalPaid', 
            'totalDue'
        ));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Supplier $supplier, UpdateSupplierRequest $request)
    {
        if ($supplier->is_default == 1) {
            Flash::error(__('messages.common.this_action_is_not_allowed_for_default_record'));

            return redirect(route('suppliers.index'));
        }

        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $this->supplierRepository->update($input, $supplier);

        Flash::success(__('messages.advanced_payment.supplier').' '.__('messages.common.updated_successfully'));

        return redirect(route('suppliers.index'));
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->is_default == 1) {
            return $this->sendError(__('messages.common.this_action_is_not_allowed_for_default_record'));
        }

        // $supplierModels = [
        //     BirthReport::class, DeathReport::class, InvestigationReport::class, OperationReport::class,
        //     Appointment::class, BedAssign::class, SupplierAdmission::class, SupplierCase::class, Bill::class,
        //     Invoice::class, AdvancedPayment::class, Prescription::class, IpdSupplierDepartment::class,
        // ];
        // $result = canDelete($supplierModels, 'supplier_id', $supplier->id);

        // if ($result) {
        //     return $this->sendError(__('messages.advanced_payment.supplier').' '.__('messages.common.cant_be_deleted'));
        // }

        // $supplier->supplierUser()->delete();
        // $supplier->address()->delete();
        $supplier->delete();

        return $this->sendSuccess(__('messages.advanced_payment.supplier').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeactiveStatus($id)
    {
        $supplier = Supplier::find($id);
        $status = ! $supplier->status;
        $supplier->update(['status' => $status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function supplierExport()
    {
        return Excel::download(new SupplierExport, 'suppliers-'.time().'.xlsx');
    }

    
    public function getSupplierDetails($id)
    {
        $supplier = Supplier::where('id',$id)->first();

        return response()->json(['phone' => $supplier->phone]);
    }
}
