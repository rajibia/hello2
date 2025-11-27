<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Setting;
use App\Repositories\InvoiceRepository;
use Illuminate\Http\Request;
use \PDF;
use DB;
use Exception;

class InvoiceController extends AppBaseController
{
    /** @var InvoiceRepository */
    private $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepo)
    {
        $this->invoiceRepository = $invoiceRepo;
    }

    public function index()
    {
        $statusArr = Invoice::STATUS_ARR;

        return view('invoices.index')->with('statusArr', $statusArr);
    }

    public function create(Request $request)
    {
        $data = $this->invoiceRepository->getSyncList();
        $data['patient_id'] = $request->query()['ref_p_id'] ?? '';

        return view('invoices.create')->with($data);
    }

    public function store(CreateInvoiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $bill = $this->invoiceRepository->saveInvoice($request->all());
            $this->invoiceRepository->saveNotification($request->all());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        if ($request->ajax()) {
            return $this->sendResponse($bill, __('messages.invoice.invoice').' '.__('messages.common.saved_successfully'));
        }

        // Redirect to the invoice detail page instead of the index
        return redirect()->route('invoices.show', $bill->id)
            ->with('success', __('messages.invoice.invoice').' '.__('messages.common.saved_successfully'));
    }

    public function show(Invoice $invoice)
    {
        $data['hospitalAddress'] = Setting::where('key', '=', 'hospital_address')->first()->value;
        $data['invoice'] = Invoice::with(['invoiceItems.charge', 'patient.address'])->find($invoice->id);

        return view('invoices.show')->with($data);
    }

    public function edit(Invoice $invoice)
    {
        $invoice->invoiceItems;
        $data = $this->invoiceRepository->getSyncList();
        $data['invoice'] = $invoice;

        // return $data;

        return view('invoices.edit')->with($data);
    }

    public function update(Invoice $invoice, UpdateInvoiceRequest $request)
    {
        try {
            DB::beginTransaction();
            $bill = $this->invoiceRepository->updateInvoice($invoice->id, $request->all());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($bill, __('messages.invoice.invoice').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(Invoice $invoice)
    {
        $this->invoiceRepository->delete($invoice->id);

        return $this->sendSuccess(__('messages.invoice.invoice').' '.__('messages.common.deleted_successfully'));
    }

    public function convertToPdf(Invoice $invoice)
    {
        $invoice->invoiceItems;
        $data = $this->invoiceRepository->getSyncListForCreate($invoice->id);
        $data['invoice'] = $invoice;
        $data['currencySymbol'] = getCurrencySymbol();
        
        // $pdf = PDF::loadView('invoices.invoice_pdf', $data);

        // return $pdf->stream('invoice.pdf');
        return view('invoices.invoice_pdf')->with('target', '_blank')->with($data);
    }
}
