<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    //
    public function index()
    {
        return view('company.index');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_name' => 'required',
            'company_code' => 'required',
        ]);
        $company = new Company();
        $company->name = $request->company_name;
        $company->code = $request->company_code;
        $company->save();
        return redirect()->back()
            ->with('success', 'Company created successfully!');
    }
    public function getCompanies()
    {
        return Company::orderBy('id')->get();
    }
    public function create()
    {
        return view('company.create');
    }

    public function edit(Company $company)
    {
        return view('company.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20',
            'is_active' => 'required|boolean',
        ]);
        $company->name = $request->name;
        $company->code = $request->code;
        $company->is_active = $request->is_active;
        $company->save();
        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    public function show(Company $company, Request $request)
    {
        $search = $request->get('search');
        $from = $request->get('from');
        $to = $request->get('to');

        // Build the query for patients with all types of bills
        $patientsQuery = $company->patients()
            ->with([
                'invoices' => function($query) use ($from, $to) {
                if ($from && $to) {
                    $query->whereDate('invoice_date', '>=', $from)
                          ->whereDate('invoice_date', '<=', $to);
                }
                $query->with('invoiceItems.charge.chargeCategory');
                },
                'medicine_bills' => function($query) use ($from, $to) {
                    if ($from && $to) {
                        $query->whereDate('created_at', '>=', $from)
                              ->whereDate('created_at', '<=', $to);
                    }
                    $query->with('saleMedicine.medicine');
                },
                'ipd_bills' => function($query) use ($from, $to) {
                    if ($from && $to) {
                        $query->whereDate('created_at', '>=', $from)
                              ->whereDate('created_at', '<=', $to);
                    }
                    $query->with('bill');
                },
                'pathologyTests' => function($query) use ($from, $to) {
                    if ($from && $to) {
                        $query->whereDate('created_at', '>=', $from)
                              ->whereDate('created_at', '<=', $to);
                    }
                    $query->with('pathologyTestItems.pathologytesttemplate');
                },
                'radiologyTests' => function($query) use ($from, $to) {
                    if ($from && $to) {
                        $query->whereDate('created_at', '>=', $from)
                              ->whereDate('created_at', '<=', $to);
                    }
                    $query->with('radiologyTestItems.radiologytesttemplate');
                },
                'maternity' => function($query) use ($from, $to) {
                    if ($from && $to) {
                        $query->whereDate('created_at', '>=', $from)
                              ->whereDate('created_at', '<=', $to);
                    }
                }
            ], 'user');

        // Apply search filter if provided
        if ($search) {
            $patientsQuery->whereHas('user', function($query) use ($search) {
                $query->where(function($userQuery) use ($search) {
                    $userQuery->where('first_name', 'LIKE', "%{$search}%")
                              ->orWhere('last_name', 'LIKE', "%{$search}%")
                              ->orWhere('phone', 'LIKE', "%{$search}%")
                              ->orWhere('email', 'LIKE', "%{$search}%")
                              ->orWhere('insurance_number', 'LIKE', "%{$search}%")
                              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                });
            })
            ->orWhereHas('invoices', function($query) use ($search) {
                $query->where('invoice_id', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('medicine_bills', function($query) use ($search) {
                $query->where('bill_number', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('pathologyTests', function($query) use ($search) {
                $query->where('test_id', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('radiologyTests', function($query) use ($search) {
                $query->where('test_id', 'LIKE', "%{$search}%");
            });
        }

        // Apply date filter for all bill types if provided
        if ($from && $to) {
            $patientsQuery->where(function($query) use ($from, $to) {
                $query->whereHas('invoices', function($q) use ($from, $to) {
                    $q->whereDate('invoice_date', '>=', $from)
                      ->whereDate('invoice_date', '<=', $to);
                })
                ->orWhereHas('medicine_bills', function($q) use ($from, $to) {
                    $q->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to);
                })
                ->orWhereHas('ipd_bills', function($q) use ($from, $to) {
                    $q->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to);
                })
                ->orWhereHas('pathologyTests', function($q) use ($from, $to) {
                    $q->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to);
                })
                ->orWhereHas('radiologyTests', function($q) use ($from, $to) {
                    $q->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to);
                })
                ->orWhereHas('maternity', function($q) use ($from, $to) {
                    $q->whereDate('created_at', '>=', $from)
                      ->whereDate('created_at', '<=', $to);
                });
            });
        }

        $company->patients = $patientsQuery->get();

        return view('company.show', compact('company'));
    }

    public function claims(Company $company, Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $perPage = $request->input('per_page', 20); // Default 20 items per page
        $billType = $request->input('bill_type'); // Filter by bill type
        $patientId = $request->input('patient_id'); // Filter by specific patient
        $paymentStatus = $request->input('payment_status'); // Filter by payment status

        // Default to current month if no dates provided
        if (!$fromDate || !$toDate) {
            $fromDate = now()->startOfMonth()->format('Y-m-d');
            $toDate = now()->endOfMonth()->format('Y-m-d');
        }

        // Ensure proper date format
        $fromDate = date('Y-m-d', strtotime($fromDate));
        $toDate = date('Y-m-d', strtotime($toDate));

        // Build the base query for patients - get all patients first, then filter bills
        $baseQuery = $company->patients();

        // Apply patient filter if specified
        if ($patientId) {
            $baseQuery->where('id', $patientId);
        }

        // Apply bill type filter if specified
        if ($billType) {
            $baseQuery->where(function($query) use ($billType) {
                switch ($billType) {
                    case 'opd_invoice':
                        $query->whereHas('invoices');
                        break;
                    case 'medicine_bill':
                        $query->whereHas('medicine_bills');
                        break;
                    case 'ipd_bill':
                        $query->whereHas('ipd_bills');
                        break;
                    case 'pathology_test':
                        $query->whereHas('pathologyTests');
                        break;
                    case 'radiology_test':
                        $query->whereHas('radiologyTests');
                        break;
                    case 'maternity':
                        $query->whereHas('maternity');
                        break;
                }
            });
        }

        // Get paginated patients
        $patientsWithBills = $baseQuery->with('user')->paginate($perPage);
        $patientsWithBills->appends($request->query());

        // Now get the full data for the paginated patients
        $patients = collect();
        foreach ($patientsWithBills as $patient) {
            $patientWithBills = $company->patients()
                ->with([
                    'invoices' => function($query) use ($fromDate, $toDate, $billType) {
                $query->whereDate('invoice_date', '>=', $fromDate)
                      ->whereDate('invoice_date', '<=', $toDate)
                      ->with('invoiceItems.charge.chargeCategory');
                    },
                    'medicine_bills' => function($query) use ($fromDate, $toDate, $billType) {
                        $query->whereDate('created_at', '>=', $fromDate)
                              ->whereDate('created_at', '<=', $toDate)
                              ->with('saleMedicine.medicine');
                    },
                    'ipd_bills' => function($query) use ($fromDate, $toDate, $billType) {
                        $query->whereDate('created_at', '>=', $fromDate)
                              ->whereDate('created_at', '<=', $toDate)
                              ->with('bill');
                    },
                    'pathologyTests' => function($query) use ($fromDate, $toDate, $billType) {
                        $query->whereDate('created_at', '>=', $fromDate)
                              ->whereDate('created_at', '<=', $toDate)
                              ->with('pathologyTestItems.pathologytesttemplate');
                    },
                    'radiologyTests' => function($query) use ($fromDate, $toDate, $billType) {
                        $query->whereDate('created_at', '>=', $fromDate)
                              ->whereDate('created_at', '<=', $toDate)
                              ->with('radiologyTestItems.radiologytesttemplate');
                    },
                    'maternity' => function($query) use ($fromDate, $toDate, $billType) {
                        $query->whereDate('created_at', '>=', $fromDate)
                              ->whereDate('created_at', '<=', $toDate);
                    },
                    'user'
                ])
                ->where('id', $patient->id)
                ->first();

            if ($patientWithBills) {
                // Apply bill type filter to the loaded data
                if ($billType) {
                    $filteredPatient = clone $patientWithBills;

                    switch ($billType) {
                        case 'opd_invoice':
                            $filteredPatient->setRelation('medicine_bills', collect());
                            $filteredPatient->setRelation('ipd_bills', collect());
                            $filteredPatient->setRelation('pathologyTests', collect());
                            $filteredPatient->setRelation('radiologyTests', collect());
                            $filteredPatient->setRelation('maternity', collect());
                            break;
                        case 'medicine_bill':
                            $filteredPatient->setRelation('invoices', collect());
                            $filteredPatient->setRelation('ipd_bills', collect());
                            $filteredPatient->setRelation('pathologyTests', collect());
                            $filteredPatient->setRelation('radiologyTests', collect());
                            $filteredPatient->setRelation('maternity', collect());
                            break;
                        case 'ipd_bill':
                            $filteredPatient->setRelation('invoices', collect());
                            $filteredPatient->setRelation('medicine_bills', collect());
                            $filteredPatient->setRelation('pathologyTests', collect());
                            $filteredPatient->setRelation('radiologyTests', collect());
                            $filteredPatient->setRelation('maternity', collect());
                            break;
                        case 'pathology_test':
                            $filteredPatient->setRelation('invoices', collect());
                            $filteredPatient->setRelation('medicine_bills', collect());
                            $filteredPatient->setRelation('ipd_bills', collect());
                            $filteredPatient->setRelation('radiologyTests', collect());
                            $filteredPatient->setRelation('maternity', collect());
                            break;
                        case 'radiology_test':
                            $filteredPatient->setRelation('invoices', collect());
                            $filteredPatient->setRelation('medicine_bills', collect());
                            $filteredPatient->setRelation('ipd_bills', collect());
                            $filteredPatient->setRelation('pathologyTests', collect());
                            $filteredPatient->setRelation('maternity', collect());
                            break;
                        case 'maternity':
                            $filteredPatient->setRelation('invoices', collect());
                            $filteredPatient->setRelation('medicine_bills', collect());
                            $filteredPatient->setRelation('ipd_bills', collect());
                            $filteredPatient->setRelation('pathologyTests', collect());
                            $filteredPatient->setRelation('radiologyTests', collect());
                            break;
                    }

                    $patients->push($filteredPatient);
                } else {
                    $patients->push($patientWithBills);
                }
            }
        }

        // Apply payment status filter to the displayed data
        if ($paymentStatus) {
            $patients = $patients->map(function($patient) use ($paymentStatus) {
                $filteredPatient = clone $patient;

                // Filter invoices
                if ($paymentStatus === 'paid') {
                    $filteredPatient->setRelation('invoices', $patient->invoices->filter(function($invoice) {
                        return $invoice->balance <= 0;
                    }));
                } elseif ($paymentStatus === 'unpaid') {
                    $filteredPatient->setRelation('invoices', $patient->invoices->filter(function($invoice) {
                        return $invoice->balance > 0;
                    }));
                }

                // Filter medicine bills
                if ($paymentStatus === 'paid') {
                    $filteredPatient->setRelation('medicine_bills', $patient->medicine_bills->filter(function($bill) {
                        return $bill->balance <= 0;
                    }));
                } elseif ($paymentStatus === 'unpaid') {
                    $filteredPatient->setRelation('medicine_bills', $patient->medicine_bills->filter(function($bill) {
                        return $bill->balance > 0;
                    }));
                }

                // Filter IPD bills
                if ($paymentStatus === 'paid') {
                    $filteredPatient->setRelation('ipd_bills', $patient->ipd_bills->filter(function($ipdPatient) {
                        return $ipdPatient->bill && ($ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments) <= 0;
                    }));
                } elseif ($paymentStatus === 'unpaid') {
                    $filteredPatient->setRelation('ipd_bills', $patient->ipd_bills->filter(function($ipdPatient) {
                        return $ipdPatient->bill && ($ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments) > 0;
                    }));
                }

                // Filter pathology tests
                if ($paymentStatus === 'paid') {
                    $filteredPatient->setRelation('pathologyTests', $patient->pathologyTests->filter(function($test) {
                        return $test->balance <= 0;
                    }));
                } elseif ($paymentStatus === 'unpaid') {
                    $filteredPatient->setRelation('pathologyTests', $patient->pathologyTests->filter(function($test) {
                        return $test->balance > 0;
                    }));
                }

                // Filter radiology tests
                if ($paymentStatus === 'paid') {
                    $filteredPatient->setRelation('radiologyTests', $patient->radiologyTests->filter(function($test) {
                        return $test->balance <= 0;
                    }));
                } elseif ($paymentStatus === 'unpaid') {
                    $filteredPatient->setRelation('radiologyTests', $patient->radiologyTests->filter(function($test) {
                        return $test->balance > 0;
                    }));
                }

                // Filter maternity
                if ($paymentStatus === 'paid') {
                    $filteredPatient->setRelation('maternity', $patient->maternity->filter(function($maternity) {
                        return $maternity->balance <= 0;
                    }));
                } elseif ($paymentStatus === 'unpaid') {
                    $filteredPatient->setRelation('maternity', $patient->maternity->filter(function($maternity) {
                        return $maternity->balance > 0;
                    }));
                }

                return $filteredPatient;
            });
        }

        // Calculate summary data for all patients (not just current page)
        $allPatientsForSummary = $company->patients()
            ->with([
                'invoices' => function($query) use ($fromDate, $toDate) {
                    $query->whereDate('invoice_date', '>=', $fromDate)
                          ->whereDate('invoice_date', '<=', $toDate);
                },
                'medicine_bills' => function($query) use ($fromDate, $toDate) {
                    $query->whereDate('created_at', '>=', $fromDate)
                          ->whereDate('created_at', '<=', $toDate);
                },
                'ipd_bills' => function($query) use ($fromDate, $toDate) {
                    $query->whereDate('created_at', '>=', $fromDate)
                          ->whereDate('created_at', '<=', $toDate);
                },
                'pathologyTests' => function($query) use ($fromDate, $toDate) {
                    $query->whereDate('created_at', '>=', $fromDate)
                          ->whereDate('created_at', '<=', $toDate);
                },
                'radiologyTests' => function($query) use ($fromDate, $toDate) {
                    $query->whereDate('created_at', '>=', $fromDate)
                          ->whereDate('created_at', '<=', $toDate);
                },
                'maternity' => function($query) use ($fromDate, $toDate) {
                    $query->whereDate('created_at', '>=', $fromDate)
                          ->whereDate('created_at', '<=', $toDate);
                }
            ])
            ->get();

        $totalBills = 0;
        $totalPatients = $allPatientsForSummary->count();
        $totalInvoices = 0;
        $totalMedicineBills = 0;
        $totalIpdBills = 0;
        $totalPathologyTests = 0;
        $totalRadiologyTests = 0;
        $totalMaternity = 0;
        $totalPaid = 0;
        $totalUnpaid = 0;

        foreach ($allPatientsForSummary as $patient) {
            // OPD Invoices
            foreach ($patient->invoices as $invoice) {
                $totalBills += $invoice->amount;
                $totalInvoices++;
                $totalPaid += ($invoice->amount - $invoice->balance);
                $totalUnpaid += $invoice->balance;
            }

            // Medicine Bills
            foreach ($patient->medicine_bills as $medicineBill) {
                $totalBills += $medicineBill->net_amount;
                $totalMedicineBills++;
                $totalPaid += ($medicineBill->net_amount - $medicineBill->balance);
                $totalUnpaid += $medicineBill->balance;
            }

            // IPD Bills
            foreach ($patient->ipd_bills as $ipdPatient) {
                if ($ipdPatient->bill) {
                    $totalBills += $ipdPatient->bill->net_payable_amount;
                    $totalIpdBills++;
                    $totalPaid += $ipdPatient->bill->total_payments;
                    $totalUnpaid += ($ipdPatient->bill->net_payable_amount - $ipdPatient->bill->total_payments);
                }
            }

            // Pathology Tests
            foreach ($patient->pathologyTests as $pathologyTest) {
                $totalBills += $pathologyTest->total;
                $totalPathologyTests++;
                $totalPaid += ($pathologyTest->total - $pathologyTest->balance);
                $totalUnpaid += $pathologyTest->balance;
            }

            // Radiology Tests
            foreach ($patient->radiologyTests as $radiologyTest) {
                $totalBills += $radiologyTest->total;
                $totalRadiologyTests++;
                $totalPaid += ($radiologyTest->total - $radiologyTest->balance);
                $totalUnpaid += $radiologyTest->balance;
            }

            // Maternity
            foreach ($patient->maternity as $maternity) {
                $totalBills += $maternity->standard_charge;
                $totalMaternity++;
                $totalPaid += ($maternity->standard_charge - $maternity->balance);
                $totalUnpaid += $maternity->balance;
            }
        }

        $totalAllBills = $totalInvoices + $totalMedicineBills + $totalIpdBills + $totalPathologyTests + $totalRadiologyTests + $totalMaternity;

        $summaryData = [
            'total_bills' => $totalBills,
            'total_patients' => $totalPatients,
            'total_invoices' => $totalInvoices,
            'total_medicine_bills' => $totalMedicineBills,
            'total_ipd_bills' => $totalIpdBills,
            'total_pathology_tests' => $totalPathologyTests,
            'total_radiology_tests' => $totalRadiologyTests,
            'total_maternity' => $totalMaternity,
            'total_all_bills' => $totalAllBills,
            'total_paid' => $totalPaid,
            'total_unpaid' => $totalUnpaid,
            'from_date' => $fromDate,
            'to_date' => $toDate
        ];

        // Get all patients for dropdown
        $allPatients = $company->patients()->with('user')->get();

        return view('company.claims', compact('company', 'patients', 'patientsWithBills', 'summaryData', 'allPatients'));
    }

    public function processPayment(Request $request, Company $company)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0.01',
            'bill_id' => 'required|string', // Changed from invoice_id to bill_id
            'payment_type' => 'required|in:0,1,2', // 0=Cash, 1=Cheque, 2=Other
            'paid_amount' => 'nullable|numeric|min:0',
            'change' => 'nullable|numeric|min:0',
            'payment_note' => 'nullable|string|max:500'
        ]);

        $paymentAmount = $request->payment_amount;
        $billId = $request->bill_id; // Format: "type_id" (e.g., "invoice_123", "medicine_456")
        $paymentType = $request->payment_type;
        $paidAmount = $request->paid_amount;
        $change = $request->change ?? 0;
        $paymentNote = $request->payment_note;

        // Parse bill type and ID
        $billParts = explode('_', $billId, 2);
        if (count($billParts) !== 2) {
            return redirect()->back()->with('error', 'Invalid bill ID format.');
        }

        $billType = $billParts[0];
        $billIdValue = $billParts[1];

        // Validate cash payment
        if ($paymentType == '0' && $paidAmount < $paymentAmount) {
            return redirect()->back()->with('error', 'Paid amount must be at least equal to payment amount for cash transactions.');
        }

        // Get the bill based on type
        $bill = null;
        $patient = null;
        $billNumber = '';
        $currentBalance = 0;

        switch ($billType) {
            case 'invoice':
                $bill = \App\Models\Invoice::find($billIdValue);
                if ($bill) {
                    $patient = $bill->patient;
                    $billNumber = $bill->invoice_id;
                    $currentBalance = $bill->balance;
                }
                break;
            case 'medicine':
                $bill = \App\Models\MedicineBill::find($billIdValue);
                if ($bill) {
                    $patient = $bill->patient;
                    $billNumber = $bill->bill_number;
                    $currentBalance = $bill->balance_amount;
                }
                break;
            case 'ipd':
                $ipdPatient = \App\Models\IpdPatientDepartment::find($billIdValue);
                if ($ipdPatient && $ipdPatient->bill) {
                    $bill = $ipdPatient->bill;
                    $patient = $ipdPatient->patient;
                    $billNumber = 'IPD-' . $ipdPatient->ipd_number;
                    $currentBalance = $bill->net_payable_amount - $bill->total_payments;
                }
                break;
            case 'pathology':
                $bill = \App\Models\PathologyTest::find($billIdValue);
                if ($bill) {
                    $patient = $bill->patient;
                    $billNumber = $bill->test_id;
                    $currentBalance = $bill->balance;
                }
                break;
            case 'radiology':
                $bill = \App\Models\RadiologyTest::find($billIdValue);
                if ($bill) {
                    $patient = $bill->patient;
                    $billNumber = $bill->test_id;
                    $currentBalance = $bill->balance;
                }
                break;
            case 'maternity':
                $bill = \App\Models\Maternity::find($billIdValue);
                if ($bill) {
                    $patient = $bill->patient;
                    $billNumber = 'MAT-' . $bill->id;
                    $currentBalance = $bill->balance;
                }
                break;
            default:
                return redirect()->back()->with('error', 'Invalid bill type.');
        }

        if (!$bill || !$patient) {
            return redirect()->back()->with('error', 'Bill not found.');
        }

        if ($currentBalance <= 0) {
            return redirect()->back()->with('error', 'This bill is already paid.');
        }

        if ($paymentAmount > $currentBalance) {
            return redirect()->back()->with('error', 'Payment amount cannot exceed bill balance.');
        }

        \DB::transaction(function () use ($bill, $billType, $paymentAmount, $company, $paymentType, $paidAmount, $change, $paymentNote, $patient, $billNumber, $currentBalance, $billIdValue) {
            $paymentDate = now();
            $originalBalance = $currentBalance;

            // Update bill based on type
            switch ($billType) {
                case 'invoice':
                    // For invoices, we don't update paid_amount directly, just balance
                    $bill->balance -= $paymentAmount;
                    if ($bill->balance <= 0) {
                        $bill->status = 0; // Paid
                        $bill->balance = 0;
                    }
                    $bill->save();
                    break;
                case 'medicine':
                    // For medicine bills, update paid_amount first
                    $bill->paid_amount = ($bill->paid_amount ?? 0) + $paymentAmount;
                    $bill->balance_amount -= $paymentAmount;
                    if ($bill->balance_amount <= 0) {
                        $bill->payment_status = 1; // Full Paid
                        $bill->balance_amount = 0;
                    }
                    $bill->save();
                    break;
                case 'ipd':
                    $ipdPatient = \App\Models\IpdPatientDepartment::find($billIdValue);
                    if ($ipdPatient && $ipdPatient->bill) {
                        $ipdPatient->bill->total_payments += $paymentAmount;
                        $ipdPatient->bill->save();
                    }
                    break;
                case 'pathology':
                    // For pathology tests, use amount_paid not paid_amount
                    $bill->amount_paid = ($bill->amount_paid ?? 0) + $paymentAmount;
                    $bill->balance -= $paymentAmount;
                    if ($bill->balance <= 0) {
                        // PathologyTest doesn't have payment_status, just set balance to 0
                        $bill->balance = 0;
                    }
                    $bill->save();
                    break;
                case 'radiology':
                    // For radiology tests, use amount_paid not paid_amount
                    $bill->amount_paid = ($bill->amount_paid ?? 0) + $paymentAmount;
                    $bill->balance -= $paymentAmount;
                    if ($bill->balance <= 0) {
                        // RadiologyTest doesn't have payment_status, just set balance to 0
                        $bill->balance = 0;
                    }
                    $bill->save();
                    break;
                case 'maternity':
                    // For maternity, check if it has amount_paid or paid_amount
                    if (property_exists($bill, 'amount_paid') || array_key_exists('amount_paid', $bill->getFillable())) {
                        $bill->amount_paid = ($bill->amount_paid ?? 0) + $paymentAmount;
                    }
                    $bill->balance -= $paymentAmount;
                    if ($bill->balance <= 0) {
                        // Maternity doesn't have payment_status, just set balance to 0
                        $bill->balance = 0;
                }
                    $bill->save();
                    break;
            }

                // Create payment record
                \App\Models\Payment::create([
                    'payment_date' => $paymentDate,
                    'account_id' => 1, // Default account - you may want to make this configurable
                    'pay_to' => $company->name,
                'amount' => $paymentAmount,
                'description' => "Payment for {$billType} #{$billNumber} - Patient: {$patient->user->full_name}",
                    'payment_type' => $paymentType,
                'paid_amount' => $paymentType == '0' ? $paidAmount : $paymentAmount,
                    'change_amount' => $paymentType == '0' ? $change : 0,
                    'payment_note' => $paymentNote,
                'invoice_id' => $billType == 'invoice' ? $bill->id : null
                ]);

            // Track processed bill for notification/logging
            $processedInvoices = [[
                'invoice_id' => $billNumber,
                'patient_name' => $patient->user->full_name,
                'payment_amount' => $paymentAmount,
                    'previous_balance' => $originalBalance,
                'new_balance' => $currentBalance - $paymentAmount,
                'status_changed' => $originalBalance > 0 && ($currentBalance - $paymentAmount) == 0
            ]];

            // Store processed invoices in session for detailed success message
            session(['processed_invoices' => $processedInvoices]);
        });

        return redirect()->back()->with('success', 'Payment of $' . number_format($paymentAmount, 2) . ' processed successfully.');
    }

    public function bulkPayment(Request $request, Company $company)
    {
        try {
                    $request->validate([
            'bills' => 'required|array|min:1',
            'bills.*.billId' => 'required',
            'bills.*.billType' => 'required|string',
            'bills.*.balance' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

            $bills = $request->bills;
            $paymentMethod = $request->payment_method;
            $paidDate = now(); // Automatically set paid date to current date/time
            $referenceNumber = $request->reference_number;
            $notes = $request->notes;

            $totalAmount = 0;
            $processedBills = [];
            $errors = [];

            \DB::beginTransaction();

            foreach ($bills as $billData) {
                $billId = $billData['billId'];
                $billType = $billData['billType'];
                $balance = floatval($billData['balance']);
                $patientName = $billData['patientName'] ?? 'Unknown Patient';
                $billNumber = $billData['billNumber'] ?? 'Unknown';

                $totalAmount += $balance;

                try {
                    // Process each bill based on type
                    switch ($billType) {
                        case 'opd_invoice':
                            $bill = \App\Models\Invoice::find($billId);
                            if ($bill && $bill->balance > 0) {
                                $bill->balance -= $balance;
                                if ($bill->balance <= 0) {
                                    $bill->status = 0; // Paid
                                    $bill->balance = 0;
                                }
                                $bill->save();

                                // Create payment record
                                \App\Models\Payment::create([
                                    'payment_date' => $paidDate,
                                    'account_id' => 1,
                                    'pay_to' => $company->name,
                                    'amount' => $balance,
                                    'description' => "Bulk payment for OPD Invoice #{$billNumber} - Patient: {$patientName}",
                                    'payment_type' => $paymentMethod,
                                    'paid_amount' => $balance,
                                    'change_amount' => 0,
                                    'payment_note' => $notes,
                                    'invoice_id' => $bill->id
                                ]);

                                $processedBills[] = [
                                    'bill_number' => $billNumber,
                                    'patient_name' => $patientName,
                                    'amount' => $balance,
                                    'type' => 'OPD Invoice'
                                ];
                            }
                            break;

                        case 'medicine_bill':
                            $bill = \App\Models\MedicineBill::find($billId);
                            if ($bill && $bill->balance_amount > 0) {
                                $bill->paid_amount = ($bill->paid_amount ?? 0) + $balance;
                                $bill->balance_amount -= $balance;
                                if ($bill->balance_amount <= 0) {
                                    $bill->payment_status = 1; // Full Paid
                                    $bill->balance_amount = 0;
                                }
                                $bill->save();

                                // Create payment record
                                \App\Models\Payment::create([
                                    'payment_date' => $paidDate,
                                    'account_id' => 1,
                                    'pay_to' => $company->name,
                                    'amount' => $balance,
                                    'description' => "Bulk payment for Medicine Bill #{$billNumber} - Patient: {$patientName}",
                                    'payment_type' => $paymentMethod,
                                    'paid_amount' => $balance,
                                    'change_amount' => 0,
                                    'payment_note' => $notes
                                ]);

                                $processedBills[] = [
                                    'bill_number' => $billNumber,
                                    'patient_name' => $patientName,
                                    'amount' => $balance,
                                    'type' => 'Medicine Bill'
                                ];
                            }
                            break;

                        case 'ipd_bill':
                            $ipdPatient = \App\Models\IpdPatientDepartment::find($billId);
                            if ($ipdPatient && $ipdPatient->bill) {
                                $ipdPatient->bill->total_payments += $balance;
                                $ipdPatient->bill->save();

                                // Create payment record
                                \App\Models\Payment::create([
                                    'payment_date' => $paidDate,
                                    'account_id' => 1,
                                    'pay_to' => $company->name,
                                    'amount' => $balance,
                                    'description' => "Bulk payment for IPD Bill #{$billNumber} - Patient: {$patientName}",
                                    'payment_type' => $paymentMethod,
                                    'paid_amount' => $balance,
                                    'change_amount' => 0,
                                    'payment_note' => $notes
                                ]);

                                $processedBills[] = [
                                    'bill_number' => $billNumber,
                                    'patient_name' => $patientName,
                                    'amount' => $balance,
                                    'type' => 'IPD Bill'
                                ];
                            }
                            break;

                        case 'pathology_test':
                            $bill = \App\Models\PathologyTest::find($billId);
                            if ($bill && $bill->balance > 0) {
                                $bill->amount_paid = ($bill->amount_paid ?? 0) + $balance;
                                $bill->balance -= $balance;
                                if ($bill->balance <= 0) {
                                    $bill->balance = 0;
                                }
                                $bill->save();

                                // Create payment record
                                \App\Models\Payment::create([
                                    'payment_date' => $paidDate,
                                    'account_id' => 1,
                                    'pay_to' => $company->name,
                                    'amount' => $balance,
                                    'description' => "Bulk payment for Pathology Test #{$billNumber} - Patient: {$patientName}",
                                    'payment_type' => $paymentMethod,
                                    'paid_amount' => $balance,
                                    'change_amount' => 0,
                                    'payment_note' => $notes
                                ]);

                                $processedBills[] = [
                                    'bill_number' => $billNumber,
                                    'patient_name' => $patientName,
                                    'amount' => $balance,
                                    'type' => 'Pathology Test'
                                ];
                            }
                            break;

                        case 'radiology_test':
                            $bill = \App\Models\RadiologyTest::find($billId);
                            if ($bill && $bill->balance > 0) {
                                $bill->amount_paid = ($bill->amount_paid ?? 0) + $balance;
                                $bill->balance -= $balance;
                                if ($bill->balance <= 0) {
                                    $bill->balance = 0;
                                }
                                $bill->save();

                                // Create payment record
                                \App\Models\Payment::create([
                                    'payment_date' => $paidDate,
                                    'account_id' => 1,
                                    'pay_to' => $company->name,
                                    'amount' => $balance,
                                    'description' => "Bulk payment for Radiology Test #{$billNumber} - Patient: {$patientName}",
                                    'payment_type' => $paymentMethod,
                                    'paid_amount' => $balance,
                                    'change_amount' => 0,
                                    'payment_note' => $notes
                                ]);

                                $processedBills[] = [
                                    'bill_number' => $billNumber,
                                    'patient_name' => $patientName,
                                    'amount' => $balance,
                                    'type' => 'Radiology Test'
                                ];
                            }
                            break;

                        case 'maternity':
                            $bill = \App\Models\Maternity::find($billId);
                            if ($bill && $bill->balance > 0) {
                                if (property_exists($bill, 'amount_paid')) {
                                    $bill->amount_paid = ($bill->amount_paid ?? 0) + $balance;
                                }
                                $bill->balance -= $balance;
                                if ($bill->balance <= 0) {
                                    $bill->balance = 0;
                                }
                                $bill->save();

                                // Create payment record
                                \App\Models\Payment::create([
                                    'payment_date' => $paidDate,
                                    'account_id' => 1,
                                    'pay_to' => $company->name,
                                    'amount' => $balance,
                                    'description' => "Bulk payment for Maternity #{$billNumber} - Patient: {$patientName}",
                                    'payment_type' => $paymentMethod,
                                    'paid_amount' => $balance,
                                    'change_amount' => 0,
                                    'payment_note' => $notes
                                ]);

                                $processedBills[] = [
                                    'bill_number' => $billNumber,
                                    'patient_name' => $patientName,
                                    'amount' => $balance,
                                    'type' => 'Maternity'
                                ];
                            }
                            break;

                        default:
                            $errors[] = "Unknown bill type: {$billType} for bill #{$billNumber}";
                            break;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error processing {$billType} #{$billNumber}: " . $e->getMessage();
                }
            }

            if (!empty($errors)) {
                \DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Some bills could not be processed: ' . implode(', ', $errors)
                ], 400);
            }

            \DB::commit();

            // Create a summary payment record for the bulk payment
            if (!empty($processedBills)) {
                \App\Models\Payment::create([
                    'payment_date' => $paidDate,
                    'account_id' => 1,
                    'pay_to' => $company->name,
                    'amount' => $totalAmount,
                    'description' => "Bulk payment for {$company->name} - {$paymentMethod} - " . count($processedBills) . " bills processed",
                    'payment_type' => $paymentMethod,
                    'paid_amount' => $totalAmount,
                    'change_amount' => 0,
                    'payment_note' => $notes . ($referenceNumber ? " (Ref: {$referenceNumber})" : '')
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Bulk payment processed successfully!',
                'data' => [
                    'total_amount' => $totalAmount,
                    'processed_bills' => count($processedBills),
                    'bills' => $processedBills
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error processing bulk payment: ' . $e->getMessage()
            ], 500);
        }
    }
}
