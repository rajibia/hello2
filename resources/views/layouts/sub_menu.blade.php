@php
    $modules = App\Models\Module::toBase()->get();
//    $modules = App\Models\Module::cacheFor(now()->addDays())->toBase()->get();
@endphp
@role('Admin')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('dashboard*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        {{ __('messages.dashboard.dashboard') }}
    </a>
</li>
@endrole
@modulePermission('smart-patient-cards', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('smart-patient-cards*', 'generate-patient-smart-cards*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('smart-patient-cards*') ? 'active' : '' }}"
       href="{{ route('smart-patient-cards.index') }}">
        {{ __('messages.patient_id_card.patient_id_card_template') }}
    </a>
</li>
@endmodulePermission
@modulePermission('generate-patient-smart-cards', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('smart-patient-cards*', 'generate-patient-smart-cards*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('generate-patient-smart-cards*') ? 'active' : '' }}"
       href="{{ route('generate-patient-smart-cards.index') }}">
        {{ __('messages.patient_id_card.generate_patient_id_card') }}
    </a>
</li>
@endmodulePermission
@modulePermission('users', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('users*', 'admins*', 'accountants*', 'nurses*', 'lab-technicians*', 'receptionists*', 'pharmacists*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
        {{ __('messages.users') }}
    </a>
</li>
@endmodulePermission
@role('Admin|Doctor|Receptionist|Nurse|Lab Technician')
@module('IPD Patients', $modules)
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('ipds*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ (Request::is('ipds') && !Request::has('filter')) || (Request::is('ipds') && Request::query('filter') == 'current') ? 'active' : '' }}" href="{{ url('ipds') }}">
        {{ __('Current IPD') }}
    </a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('ipds*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('ipds') && Request::query('filter') == 'old' ? 'active' : '' }}" href="{{ url('ipds?filter=old') }}">
        {{ __('Old IPD') }}
    </a>
</li>
@endmodule
@module('OPD Patients', $modules)
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('opds*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('opds') && Request::query('filter') == 'upcoming' ? 'active' : '' }} " href="{{ route('opd.patient.index', ['filter' => 'upcoming']) }}">
        {{ __('Upcoming OPD') }}
    </a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('opds*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ (Request::is('opds') && !Request::has('filter')) ? 'active' : '' }} " href="{{ route('opd.patient.index') }}">
        {{ __('OPD Today') }}
    </a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('opds*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('opds') && Request::query('filter') == 'old' ? 'active' : '' }} " href="{{ route('opd.patient.index', ['filter' => 'old']) }}">
        {{ __('Old OPD') }}
    </a>
</li>
@endmodule
<!-- @module('Maternity', $modules)
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('maternity*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('maternity') && Request::query('filter') == 'upcoming' ? 'active' : '' }} " href="{{ route('maternity.index', ['filter' => 'upcoming']) }}">
        {{ __('messages.maternity.upcoming_maternity') }}
    </a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('maternity*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ (Request::is('maternity') && !Request::has('filter')) ? 'active' : '' }} " href="{{ route('maternity.index') }}">
        {{ __('messages.maternity.maternity_today') }}
    </a>
</li>
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('maternity*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('maternity') && Request::query('filter') == 'old' ? 'active' : '' }} " href="{{ route('maternity.index', ['filter' => 'old']) }}">
        {{ __('messages.maternity.old_maternity') }}
    </a>
</li>
@endmodule -->
@endrole

@modulePermission('vaccinated-patients', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('vaccinated-patients*', 'vaccinations*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('vaccinated-patients*') ? 'active' : '' }}"
       href="{{ route('vaccinated-patients.index') }}">
        {{ __('messages.vaccinated_patients') }}
    </a>
</li>
@endmodulePermission

@modulePermission('vaccinations', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('vaccinated-patients*', 'vaccinations*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('vaccinations*') ? 'active' : '' }}"
       href="{{ route('vaccinations.index') }}">
        {{ __('messages.vaccinations') }}
    </a>
</li>
@endmodulePermission
@modulePermission('admins', 'view')
{{-- @module('Accounts',$modules) --}}
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('admins*', 'users*', 'accountants*', 'nurses*', 'lab-technicians*', 'receptionists*', 'pharmacists*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('admins*') ? 'active' : '' }}" href="{{ route('admins.index') }}">
        {{ __('messages.admin') }}
    </a>
</li>
{{-- @endmodule --}}
@endmodulePermission
@modulePermission('accounts', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('manual-billing-payments*', 'accounts*', 'employee-payrolls*', 'invoices*', 'payments*', 'payment-reports*', 'advanced-payments*', 'bills*', 'billing-medicine-bills*', 'today-payment-reports*', 'company-billing*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('accounts*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
        {{ __('messages.account.account') }}
    </a>
</li>
@endmodulePermission

@modulePermission('employee-payrolls', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('manual-billing-payments*', 'accounts*', 'employee-payrolls*', 'invoices*', 'payments*', 'payment-reports*', 'advanced-payments*', 'bills*', 'billing-medicine-bills*', 'today-payment-reports*', 'company-billing*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('employee-payrolls*') ? 'active' : '' }}"
       href="{{ route('employee-payrolls.index') }}">
        {{ __('messages.employee_payrolls') }}
    </a>
</li>
@endmodulePermission
@modulePermission('invoices', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('manual-billing-payments*', 'accounts*', 'employee-payrolls*', 'invoices*', 'payments*', 'payment-reports*', 'advanced-payments*', 'bills*', 'billing-medicine-bills*', 'today-payment-reports*', 'company-billing*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('invoices*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
        {{ __('messages.invoices') }}
    </a>
</li>
@endmodulePermission

@modulePermission('payments', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('manual-billing-payments*', 'accounts*', 'employee-payrolls*', 'invoices*', 'payments*', 'payment-reports*', 'advanced-payments*', 'bills*', 'billing-medicine-bills*', 'today-payment-reports*', 'company-billing*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('payments*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
        {{ __('messages.payments') }}
    </a>
</li>
@endmodulePermission
@modulePermission('payment-reports', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('manual-billing-payments*', 'accounts*', 'employee-payrolls*', 'invoices*', 'payments*', 'payment-reports*', 'advanced-payments*', 'bills*', 'billing-medicine-bills*', 'today-payment-reports*', 'company-billing*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('payment-reports*') ? 'active' : '' }}"
       href="{{ route('payment.reports') }}">
        {{ __('messages.payment.payment_reports') }}
    </a>
</li>
@endmodulePermission

@modulePermission('today-payment-reports', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('manual-billing-payments*', 'accounts*', 'employee-payrolls*', 'invoices*', 'payments*', 'payment-reports*', 'advanced-payments*', 'bills*', 'billing-medicine-bills*', 'today-payment-reports*', 'company-billing*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('today-payment-reports*') ? 'active' : '' }}"
       href="{{ route('today-payment-reports.index') }}">
        Revenue
    </a>
</li>
@endmodulePermission
@modulePermission('advanced-payments', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('manual-billing-payments*', 'accounts*', 'employee-payrolls*', 'invoices*', 'payments*', 'payment-reports*', 'advanced-payments*', 'bills*', 'billing-medicine-bills*', 'today-payment-reports*', 'company-billing*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('advanced-payments*') ? 'active' : '' }}"
       href="{{ route('advanced-payments.index') }}">
        {{ __('messages.advanced_payments') }}
    </a>
</li>
@endmodulePermission
{{-- @role('Admin|Accountant')
    @module('Bills', $modules)
        <li
            class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('manual-billing-payments*', 'accounts*', 'employee-payrolls*', 'invoices*', 'payments*', 'payment-reports*', 'advanced-payments*', 'bills*', 'billing-medicine-bills*', 'today-payment-reports*') ? 'd-none' : '' }}">
            <a class="nav-link p-0 {{ Request::is('bills*') ? 'active' : '' }}" href="{{ route('bills.index') }}">
                {{ __('messages.bills') }}
            </a>
        </li>
    @endmodule
@endrole
@role('Admin')
    <li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('accounts*', 'employee-payrolls*', 'invoices*', 'payments*', 'payment-reports*', 'advanced-payments*', 'bills*', 'manual-billing-payments*', 'billing-medicine-bills*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('manual-billing-payments*') ? 'active' : '' }}"
            href="{{ route('manual-billing-payments.index') }}">
            {{ __('messages.bill.manual_bill') }}
        </a>
    </li>
@endrole --}}
@modulePermission('billing-medicine-bills', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('manual-billing-payments*', 'accounts*', 'employee-payrolls*', 'invoices*', 'payments*', 'payment-reports*', 'advanced-payments*', 'bills*', 'billing-medicine-bills*', 'today-payment-reports*', 'company-billing*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('accounts-medicine-bills*') ? 'active' : '' }}"
       href="{{ route('accounts-medicine-bills') }}">
        {{ __('messages.medicine_bills.medicine_bill') }}
    </a>
</li>
@endmodulePermission
@role('Admin|Accountant')
@module('Company Billing', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('manual-billing-payments*', 'accounts*', 'employee-payrolls*', 'invoices*', 'payments*', 'payment-reports*', 'advanced-payments*', 'bills*', 'billing-medicine-bills*', 'today-payment-reports*', 'company-billing*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('company-billing*') ? 'active' : '' }}"
       href="{{ route('company-billing.index') }}">
        Company Billing
    </a>
</li>
@endmodule
@endrole
@modulePermission('bed-status', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('bed-types*', 'beds*', 'bed-assigns*', 'bulk-beds', 'bed-status') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('bed-status*') ? 'active' : '' }}" href="{{ route('bed-status') }}">
        {{ __('messages.bed_status.bed_status') }}
    </a>
</li>
@endmodulePermission
@modulePermission('bed-assigns', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('bed-types*', 'beds*', 'bed-assigns*', 'bulk-beds', 'bed-status') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('bed-assigns*') ? 'active' : '' }}" href="{{ route('bed-assigns.index') }}">
        {{ __('messages.bed_assigns') }}
    </a>
</li>
@endmodulePermission

@modulePermission('beds', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('bed-types*', 'beds*', 'bed-assigns*', 'bulk-beds', 'bed-status') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('beds*') || Request::is('bulk-beds') ? 'active' : '' }}"
       href="{{ route('beds.index') }}">
        {{ __('messages.beds') }}
    </a>
</li>
@endmodulePermission
@modulePermission('bed-types', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('bed-types*', 'beds*', 'bed-assigns*', 'bulk-beds', 'bed-status') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('bed-types*') ? 'active' : '' }}" href="{{ route('bed-types.index') }}">
        {{ __('messages.bed_types') }}
    </a>
</li>
@endmodulePermission

@modulePermission('blood-banks', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('blood-banks*', 'blood-donors*', 'blood-donations*', 'blood-issues*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('blood-banks*') ? 'active' : '' }}" href="{{ route('blood-banks.index') }}">
        {{ __('messages.blood_banks') }}
    </a>
</li>
@endmodulePermission

@modulePermission('blood-donors', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('blood-banks*', 'blood-donors*', 'blood-donations*', 'blood-issues*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('blood-donors*') ? 'active' : '' }}"
       href="{{ route('blood-donors.index') }}">
        {{ __('messages.blood_donors') }}
    </a>
</li>
@endmodulePermission

@modulePermission('blood-donations', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('blood-banks*', 'blood-donors*', 'blood-donations*', 'blood-issues*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('blood-donations*') ? 'active' : '' }}"
       href="{{ route('blood-donations.index') }}">
        {{ __('messages.blood_donations') }}
    </a>
</li>
@endmodulePermission

@modulePermission('blood-issues', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('blood-banks*', 'blood-donors*', 'blood-donations*', 'blood-issues*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('blood-issues*') ? 'active' : '' }}"
       href="{{ route('blood-issues.index') }}">
        {{ __('messages.blood_issues') }}
    </a>
</li>
@endmodulePermission
@modulePermission('patients', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('patients*', 'patient-cases*', 'case-handlers*', 'patient-admissions*','companies*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('patients*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
        {{ __('messages.patients') }}
    </a>
</li>
@endmodulePermission
@modulePermission('patient-cases', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('patients*', 'patient-cases*', 'case-handlers*', 'patient-admissions*','companies*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('patient-cases*') ? 'active' : '' }}"
       href="{{ route('patient-cases.index') }}">
        {{ __('messages.cases') }}
    </a>
</li>
@endmodulePermission

@modulePermission('case-handlers', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0  {{ !Request::is('patients*', 'patient-cases*', 'case-handlers*', 'patient-admissions*','companies*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('case-handlers*') ? 'active' : '' }}"
       href="{{ route('case-handlers.index') }}">
        {{ __('messages.case_handlers') }}
    </a>
</li>
@endmodulePermission

@modulePermission('patient-admissions', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('patients*', 'patient-cases*', 'case-handlers*', 'patient-admissions*','companies*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('patient-admissions**') ? 'active' : '' }}"
       href="{{ route('patient-admissions.index') }}">
        {{ __('messages.patient_admissions') }}
    </a>
</li>
@endmodulePermission

@modulePermission('companies', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('patients*', 'patient-cases*', 'case-handlers*', 'patient-admissions*','companies*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('companies**') ? 'active' : '' }}"
       href="{{ route('companies.index') }}">
        {{ __('messages.company_admissions') }}
    </a>
</li>
@endmodulePermission

@role('Case Manager|Pharmacist|Lab Technician')
@module('Doctors', $modules)
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('employee/doctor*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('employee/doctor*') ? 'active' : '' }}"
       href="{{ url('employee/doctor') }}">
        {{ __('messages.doctors') }}
    </a>
</li>
@endmodule
@endrole
@role('Pharmacist|Nurse')
@module('Prescriptions', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('employee/prescriptions*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('employee/prescriptions*') ? 'active' : '' }}"
       href="{{ url('employee/prescriptions') }}">
        {{ __('messages.prescriptions') }}
    </a>
</li>
@endmodule
@endrole
@modulePermission('documents', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('documents*', 'document-types*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('documents*') ? 'active' : '' }}" href="{{ route('documents.index') }}">
        {{ __('messages.documents') }}
    </a>
</li>
@endmodulePermission

@modulePermission('document-types', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('documents*', 'document-types*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('document-types*') ? 'active' : '' }}"
       href="{{ route('document-types.index') }}">
        {{ __('messages.document_types') }}
    </a>
</li>
@endmodulePermission

@modulePermission('insurances', 'view')
<li   class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('insurances*', 'services*', 'scans*', 'labs*', 'diagnosis*', 'procedures*', 'packages*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('insurances*') ? 'active' : '' }}"
       href="{{ route('insurances.index') }}">
        {{ __('messages.insurances') }} {{ __('messages.setup') }}
    </a>
</li>
@endmodulePermission

@modulePermission('services', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('insurances*', 'services*', 'scans*', 'labs*', 'diagnosis*', 'procedures*', 'packages*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('services*') ? 'active' : '' }}" href="{{ route('services.index') }}">
        {{ __('messages.opd') }} {{ __('messages.services') }}
    </a>
</li>
@endmodulePermission

@modulePermission('scans', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('insurances*', 'services*', 'scans*', 'labs*', 'diagnosis*', 'procedures*', 'packages*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('scans*') ? 'active' : '' }}" href="{{ route('scans.index') }}">
        {{ __('messages.scans') }}
    </a>
</li>
@endmodulePermission

@modulePermission('labs', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('insurances*', 'services*', 'scans*', 'labs*', 'diagnosis*', 'procedures*', 'packages*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('labs*') ? 'active' : '' }}" href="{{ route('labs.index') }}">
        {{ __('messages.labs') }}
    </a>
</li>
@endmodulePermission

@modulePermission('procedures', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('insurances*', 'services*', 'scans*', 'labs*', 'diagnosis*', 'procedures*', 'packages*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('procedures*') ? 'active' : '' }}"
       href="{{ route('procedures.index') }}">
        {{ __('messages.procedures') }}
    </a>
</li>
@endmodulePermission

@modulePermission('diagnosis', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('insurances*', 'services*', 'scans*', 'labs*', 'diagnosis*', 'procedures*', 'packages*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('diagnosis*') && !Request::is('diagnosis-*') ? 'active' : '' }}"
       href="{{ route('diagnosis.index') }}">
        {{ __('messages.diagnoses') }}
    </a>
</li>
@endmodulePermission

@modulePermission('diagnosis-categories', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('insurances*', 'services*', 'scans*', 'labs*', 'diagnosis*', 'procedures*', 'packages*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('diagnosis-categories*') ? 'active' : '' }}"
       href="{{ route('diagnosis.category.index') }}">
        {{ __('messages.diagnosis_category.icd') }}
    </a>
</li>
@endmodulePermission

@modulePermission('packages', 'view')
{{-- <li
    class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('services*', 'insurances*', 'packages*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('packages*') ? 'active' : '' }}" href="{{ route('packages.index') }}">
        {{ __('messages.packages') }}
    </a>
</li> --}}
@endmodulePermission

@modulePermission('ambulances', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('ambulances*', 'ambulance-calls*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('ambulances*') ? 'active' : '' }}"
       href="{{ route('ambulances.index') }}">
        {{ __('messages.ambulances') }}
    </a>
</li>
@endmodulePermission

@modulePermission('ambulance-calls', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('ambulances*', 'ambulance-calls*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('ambulance-calls*') ? 'active' : '' }}"
       href="{{ route('ambulance-calls.index') }}">
        {{ __('messages.ambulance_calls') }}
    </a>
</li>
@endmodulePermission


{{-- <li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0"> --}}
@modulePermission('doctors', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('doctors*', 'doctor-departments*', 'schedules*', 'holidays*', 'breaks*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('doctors*') ? 'active' : '' }}" href="{{ route('doctors.index') }}">
        {{ __('messages.doctors') }}
    </a>
</li>
@endmodulePermission
@modulePermission('doctor-departments', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('doctors*', 'doctor-departments*', 'schedules*', 'holidays*', 'breaks*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('doctor-departments*') ? 'active' : '' }}"
       href="{{ route('doctor-departments.index') }}">
        <span class="menu-title" style="white-space: nowrap"> {{ __('messages.doctor_departments') }}</span>
    </a>
</li>
@endmodulePermission
@role('Admin|Doctor|Receptionist|Lab Technician')
@modulePermission('schedules', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('doctors*', 'doctor-departments*', 'schedules*', 'holidays*', 'breaks*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('schedules*') ? 'active' : '' }}" href="{{ route('schedules.index') }}">
        {{ __('messages.schedules') }}
    </a>
</li>
@endmodulePermission
@endrole
@modulePermission('prescriptions', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('prescriptions*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('prescriptions*') ? 'active' : '' }}"
       href="{{ route('prescriptions.index') }}">
        {{ __('messages.prescriptions') }}
    </a>
</li>
@endmodulePermission

@modulePermission('holidays', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('doctors*', 'doctor-departments*', 'schedules*', 'holidays*', 'breaks*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('holidays*') ? 'active' : '' }}"
       href="{{ route('holidays.index') }}">{{ __('messages.holiday.doctor_holiday') }}</a>
</li>
@endmodulePermission
@modulePermission('breaks', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('doctors*', 'doctor-departments*', 'schedules*', 'holidays*', 'breaks*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('breaks*') ? 'active' : '' }}"
       href="{{ route('breaks.index') }}">{{ __('messages.lunch_break.lunch_breaks') }}</a>
</li>
@endmodulePermission
@modulePermission('accountants', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('accountants*', 'admins*', 'users*', 'nurses*', 'lab-technicians*', 'receptionists*', 'pharmacists*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('accountants*') ? 'active' : '' }}"
       href="{{ route('accountants.index') }}">
        {{ __('messages.accountants') }}
    </a>
</li>
@endmodulePermission
@modulePermission('nurses', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('nurses*', 'admins*', 'users*', 'accountants*', 'lab-technicians*', 'receptionists*', 'pharmacists*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('nurses*') ? 'active' : '' }}" href="{{ route('nurses.index') }}">
        {{ __('messages.nurses') }}
    </a>
</li>
@endmodulePermission
{{-- <li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ (!Request::is('receptionists*')) ? 'd-none' : '' }}" --}}
{{-- > --}}
@modulePermission('receptionists', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('receptionists*', 'users*', 'admins*', 'accountants*', 'nurses*', 'lab-technicians*', 'pharmacists*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('receptionists*') ? 'active' : '' }}"
       href="{{ route('receptionists.index') }}">
        {{ __('messages.receptionists') }}
    </a>
</li>
@endmodulePermission
@modulePermission('lab-technicians', 'view')<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('lab-technicians*', 'admins*', 'users*', 'accountants*', 'nurses*', 'receptionists*', 'pharmacists*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('lab-technicians*') ? 'active' : '' }}"
       href="{{ route('lab-technicians.index') }}">
        {{ __('messages.lab_technicians') }}
    </a>
</li>
@endmodulePermission
@modulePermission('pharmacists', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('pharmacists*', 'users*', 'admins*', 'accountants*', 'nurses*', 'lab-technicians*', 'receptionists*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('pharmacists*') ? 'active' : '' }}"
       href="{{ route('pharmacists.index') }}">
        {{ __('messages.pharmacists') }}
    </a>
</li>
@endmodulePermission
@role('Nurse')
@module('Doctors', $modules)
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('doctors*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('doctors*') ? 'active' : '' }}" href="{{ route('doctors.index') }}">
        {{ __('messages.doctors') }}
    </a>
</li>
@endmodule
@endrole
@role('Nurse')
@module('Prescriptions', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('prescriptions*', 'prescription-medicine-show*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('prescriptions*', 'prescription-medicine-show*') ? 'active' : '' }}"
       href="{{ route('prescriptions.index') }}">{{ __('messages.prescriptions') }}</a>
</li>
@endmodule
@endrole
@modulePermission('appointments', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('appointments*', 'appointment-calendars') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('appointments*') ? 'active' : '' }}"
       href="{{ route('appointments.index') }}">
        {{ __('messages.appointments') }}
    </a>
</li>
@endmodulePermission
@modulePermission('birth-reports', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('birth-reports*', 'death-reports*', 'investigation-reports*', 'operation-reports*', 'employee/patient-diagnosis-test*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('birth-reports*') ? 'active' : '' }}"
       href="{{ route('birth-reports.index') }}">
        {{ __('messages.birth_reports') }}
    </a>
</li>
@endmodulePermission

@modulePermission('death-reports', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('birth-reports*', 'death-reports*', 'investigation-reports*', 'operation-reports*', 'employee/patient-diagnosis-test*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('death-reports*') ? 'active' : '' }}"
       href="{{ route('death-reports.index') }}">
        {{ __('messages.death_reports') }}
    </a>
</li>
@endmodulePermission

@modulePermission('investigation-reports', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('birth-reports*', 'death-reports*', 'investigation-reports*', 'operation-reports*', 'employee/patient-diagnosis-test*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('investigation-reports*') ? 'active' : '' }}"
       href="{{ route('investigation-reports.index') }}">
        {{ __('messages.investigation_reports') }}
    </a>
</li>
@endmodulePermission

@modulePermission('operation-reports', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('birth-reports*', 'death-reports*', 'investigation-reports*', 'operation-reports*', 'employee/patient-diagnosis-test*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('operation-reports*') ? 'active' : '' }}"
       href="{{ route('operation-reports.index') }}">
        {{ __('messages.operation_reports') }}
    </a>
</li>
@endmodulePermission


{{-- Reports Module Submenu --}}
<style>
    .reports-dropdown .dropdown-menu {
        margin-top: 0;
        transform: translate3d(0px, 10px, 0px) !important;
    }
</style>
@module('Reports', $modules)
{{-- Patient Reports Dropdown --}}
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('reports*') ? 'd-none' : '' }}">
    <div class="dropdown reports-dropdown">
        <a class="btn dropdown-toggle p-0 {{ Request::is('reports/daily-count*') || Request::is('reports') && Request::segment(2) === null ? 'active' : '' }}"
           type="button" id="patientReportsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('Patient Reports') }}
        </a>
        <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="patientReportsDropdown">
            <a class="dropdown-item {{ Request::is('reports/daily-count*') ? 'active' : '' }}" href="{{ route('reports.daily-count') }}">
                <i class="fas fa-chart-bar me-2 text-info"></i>Daily OPD & IPD Count
            </a>
            <a class="dropdown-item {{ Request::is('reports/discharge*') ? 'active' : '' }}" href="{{ route('reports.discharge') }}">
                <i class="fas fa-file-medical-alt me-2 text-success"></i>Discharge Report
            </a>
            <a class="dropdown-item {{ Request::is('reports/opd-statement*') ? 'active' : '' }}" href="{{ route('reports.opd-statement') }}">
                <i class="fas fa-file-alt me-2 text-primary"></i>OPD Statement Report
            </a>
            <a class="dropdown-item {{ Request::is('reports/monthly-outpatient-morbidity*') ? 'active' : '' }}" href="{{ route('reports.monthly-outpatient-morbidity') }}">
                <i class="fas fa-chart-pie me-2 text-warning"></i>Monthly Outpatient Morbidity Returns
            </a>
            <a class="dropdown-item {{ Request::is('reports/patient-statement*') ? 'active' : '' }}" href="{{ route('reports.patient-statement') }}">
                <i class="fas fa-file-medical me-2 text-danger"></i>Patient Statement
            </a>
        </ul>
    </div>
</li>

{{-- Financial Reports Dropdown --}}
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('reports*') ? 'd-none' : '' }}">
    <div class="dropdown reports-dropdown">
        <a class="btn dropdown-toggle p-0"
           type="button" id="financialReportsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('Financial Reports') }}
        </a>
        <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="financialReportsDropdown">
            <a class="dropdown-item {{ Request::is('reports/transaction*') ? 'active' : '' }}" href="{{ route('reports.transaction') }}">
                <i class="fas fa-money-bill-wave me-2 text-success"></i>Daily & Monthly Transaction Report
            </a>
            <a class="dropdown-item {{ Request::is('reports/opd-balance*') ? 'active' : '' }}" href="{{ route('reports.opd-balance') }}">
                <i class="fas fa-balance-scale me-2 text-primary"></i>OPD Balance Report
            </a>
            <a class="dropdown-item {{ Request::is('reports/ipd-balance*') ? 'active' : '' }}" href="{{ route('reports.ipd-balance') }}">
                <i class="fas fa-balance-scale-left me-2 text-info"></i>IPD Balance Report
            </a>
            <a class="dropdown-item {{ Request::is('reports/pharmacy-bill*') ? 'active' : '' }}" href="{{ route('reports.pharmacy-bill') }}">
                <i class="fas fa-file-invoice-dollar me-2 text-warning"></i>Pharmacy Bill Report
            </a>
            <a class="dropdown-item {{ Request::is('reports/expenses*') ? 'active' : '' }}" href="{{ route('reports.expenses') }}">
                <i class="fas fa-chart-line me-2 text-danger"></i>Expenses Report
            </a>
        </ul>
    </div>
</li>

{{-- Inventory Reports Dropdown --}}
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('reports*') ? 'd-none' : '' }}">
    <div class="dropdown reports-dropdown">
        <a class="btn dropdown-toggle p-0"
           type="button" id="inventoryReportsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('Inventory Reports') }}
        </a>
        <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="inventoryReportsDropdown">
            <a class="dropdown-item {{ Request::is('reports/medicine*') ? 'active' : '' }}" href="{{ route('reports.medicine') }}">
                <i class="fas fa-pills me-2 text-primary"></i>Medicine Report
            </a>
            <a class="dropdown-item {{ Request::is('reports/expiry-medicine*') ? 'active' : '' }}" href="{{ route('reports.expiry-medicine') }}">
                <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Expiry Medicine Report
            </a>
            <a class="dropdown-item {{ Request::is('reports/medicine-transfer*') ? 'active' : '' }}" href="{{ route('reports.medicine-transfer') }}">
                <i class="fas fa-exchange-alt me-2 text-success"></i>{{ __('messages.medicine.medicine_transfer_report') }}
            </a>
            <a class="dropdown-item {{ Request::is('reports/medicine-adjustment*') ? 'active' : '' }}" href="{{ route('reports.medicine-adjustment') }}">
                <i class="fas fa-balance-scale me-2 text-warning"></i>{{ __('messages.medicine.medicine_adjustment_report') }}
            </a>
            <a class="dropdown-item {{ Request::is('reports/company-claim*') ? 'active' : '' }}" href="{{ route('reports.company-claim') }}">
                <i class="fas fa-building me-2 text-warning"></i>Company Claim Report
            </a>
            <a class="dropdown-item {{ Request::is('reports/purchase*') ? 'active' : '' }}" href="{{ route('reports.purchase') }}">
                <i class="fas fa-shopping-cart me-2 text-danger"></i>Purchase Report
            </a>
            <a class="dropdown-item {{ Request::is('reports/stock*') ? 'active' : '' }}" href="{{ route('reports.stock') }}">
                <i class="fas fa-boxes me-2 text-info"></i>Inventory Stock Report
            </a>
        </ul>
    </div>
</li>
@endmodule

@role('Admin|Pharmacist|Lab Technician')
<!-- @module('Medicines', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('categories*', 'brands*', 'medicines*', 'medicine-purchase*', 'used-medicine*', 'medicine-bills*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('suppliers*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
        {{ __('messages.medicine_bills.medicine_suppliers') }}
    </a>
</li>
@endmodule -->
@endrole
@modulePermission('categories', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('categories*', 'brands*', 'medicines*', 'medicine-purchase*', 'used-medicine*', 'medicine-bills*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('categories*') ? 'active' : '' }}"
       href="{{ route('categories.index') }}">
        {{ __('messages.medicine_categories') }}
    </a>
</li>
@endmodulePermission
@modulePermission('brands', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('categories*', 'brands*', 'medicines*', 'medicine-purchase*', 'used-medicine*', 'medicine-bills*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('brands*') ? 'active' : '' }}" href="{{ route('brands.index') }}">
        {{ __('messages.medicine_brands') }}
    </a>
</li>
@endmodulePermission
@modulePermission('medicines', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('categories*', 'brands*', 'medicines*', 'medicine-purchase*', 'used-medicine*', 'medicine-bills*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('medicines*') ? 'active' : '' }}" href="{{ route('medicines.index') }}">
        Medicine
    </a>
</li>
@endmodulePermission
<!-- @modulePermission('near_expiry', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('categories*', 'brands*', 'medicines*', 'medicine-purchase*', 'used-medicine*', 'medicine-bills*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('medicines/near_expiry') ? 'active' : '' }}" href="{{ route('medicines.near_expiry') }}">
        Near Expiry
    </a>
</li>
@endmodulePermission
@modulePermission('expired', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('categories*', 'brands*', 'medicines*', 'medicine-purchase*', 'used-medicine*', 'medicine-bills*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('medicines/expired') ? 'active' : '' }}" href="{{ route('medicines.expired') }}">
        Already Expired
    </a>
</li>
@endmodulePermission -->

@modulePermission('medicine-purchase', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('categories*', 'brands*', 'medicines*', 'medicine-purchase*', 'used-medicine*', 'medicine-bills*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('medicine-purchase*') ? 'active' : '' }}"
       href="{{ route('medicine-purchase.index') }}">
        {{ __('messages.purchase_medicine.purchase_medicine') }}
    </a>
</li>
@endmodulePermission

@modulePermission('medicine-bills', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('categories*', 'brands*', 'medicines*', 'medicine-purchase*', 'used-medicine*', 'medicine-bills*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('medicine-bills*') ? 'active' : '' }}"
       href="{{ route('medicine-bills.index') }}">
        {{ __('messages.medicine_bills.medicine_bill') }}
    </a>
</li>
@endmodulePermission

@modulePermission('used-medicine', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('categories*', 'brands*', 'medicines*', 'medicine-purchase*', 'used-medicine*', 'medicine-bills*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('used-medicine*') ? 'active' : '' }}"
       href="{{ route('used-medicine.index') }}">{{ __('messages.used_medicine.used_medicine') }}</a>
</li>
@endmodulePermission

@modulePermission('radiology-categories', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('radiology-categories*', 'radiology-tests*', 'radiology-test-template*', 'radiology-units*', 'radiology-parameters*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('radiology-categories*') ? 'active' : '' }}"
       href="{{ route('radiology.category.index') }}">
        {{ __('messages.radiology_category.radiology_categories') }}
    </a>
</li>
@endmodulePermission

@modulePermission('radiology-test-templates', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('radiology-categories*', 'radiology-tests*', 'radiology-test-template*', 'radiology-units*', 'radiology-parameters*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('radiology-test-templates*') || Request::is('radiology-tests-templates*') ? 'active' : '' }}"
       href="{{ route('radiology.test.template.index') }}">
        {{ __('messages.radiology_test_templates') }}
    </a>
</li>
@endmodulePermission

@modulePermission('radiology-tests', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('radiology-categories*', 'radiology-tests*', 'radiology-test-template*', 'radiology-units*', 'radiology-parameters*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('radiology-tests') || Request::is('radiology-tests/*')  ? 'active' : '' }}"
       href="{{ route('radiology.test.index') }}">
        {{ __('messages.radiology_tests') }}
    </a>
</li>
@endmodulePermission

@modulePermission('pathology-categories', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('pathology-categories*', 'pathology-tests*', 'pathology-tests-templates*', 'pathology-units*', 'pathology-parameters*', 'dynamic-pathology-templates*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('pathology-categories*') ? 'active' : '' }}"
       href="{{ route('pathology.category.index') }}">
        {{ __('messages.pathology_category.pathology_categories') }}
    </a>
</li>
@endmodulePermission
@modulePermission('duty-roster', 'view')
{{-- @module('Duty Roster', $modules) --}}
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('duty-roster*', 'shifts*', 'roster*', 'assign-roster*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('duty-roster*') ? 'active' : '' }}"
       href="{{ route('duty.roster.index') }}">
        {{ __('messages.duty_roster.title') }}
    </a>
</li>
@endmodulePermission
@modulePermission('shifts', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('duty-roster*', 'shifts*', 'roster*', 'assign-roster*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('shifts*') ? 'active' : '' }}"
       href="{{ route('duty.roster.shifts.index') }}">
        {{ __('messages.duty_roster.shift') }}
    </a>
</li>
@endmodulePermission
@modulePermission('roster', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('duty-roster*', 'shifts*', 'roster*', 'assign-roster*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('roster*') ? 'active' : '' }}"
       href="{{ route('duty.roster.roster.index') }}">
        {{ __('messages.duty_roster.roster') }}
    </a>
</li>
@endmodulePermission

@modulePermission('assign-roster', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('duty-roster*', 'shifts*', 'roster*', 'assign-roster*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('assign-roster*') ? 'active' : '' }}"
       href="{{ route('duty.roster.assign.index') }}">
        {{ __('messages.duty_roster.assign_roster') }}
    </a>
</li>
{{-- @endmodule --}}
@endmodulePermission
@modulePermission('pathology-tests-templates', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('pathology-categories*', 'pathology-tests*', 'pathology-tests-templates*', 'pathology-units*', 'pathology-parameters*', 'dynamic-pathology-templates*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('pathology-tests-templates*') ? 'active' : '' }}"
       href="{{ route('pathology.test.template.index') }}">
        {{ __('messages.pathology_tests_templates') }}
    </a>
</li>
@endmodulePermission
@modulePermission('pathology-tests', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('pathology-categories*', 'pathology-tests*', 'pathology-tests-templates*', 'pathology-units*', 'pathology-parameters*', 'dynamic-pathology-templates*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('pathology-tests') || Request::is('pathology-tests/create') || Request::is('pathology-tests/*/edit') || Request::is('pathology-tests/*/show') ? 'active' : '' }}"
       href="{{ route('pathology.test.index') }}">
        {{ __('messages.pathology_tests_requests') }}
    </a>
</li>

@endmodulePermission


{{-- @role('Admin|Doctor|Receptionist|Lab Technician')
    @module('Diagnosis Categories', $modules)
        <li
            class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('diagnosis-categories*', 'patient-diagnosis-test*') ? 'd-none' : '' }}">
            <a class="nav-link p-0 {{ Request::is('diagnosis-categories*') ? 'active' : '' }}"
                href="{{ route('diagnosis.category.index') }}">
                {{ __('messages.diagnosis_category.diagnosis_categories') }}
            </a>
        </li>
    @endmodule
@endrole
@role('Admin|Doctor|Receptionist|Lab Technician|Nurse')
    @module('Diagnosis Tests', $modules)
        <li
            class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('diagnosis-categories*', 'patient-diagnosis-test*') ? 'd-none' : '' }}">
            <a class="nav-link p-0 {{ Request::is('patient-diagnosis-test*') ? 'active' : '' }}"
                href="{{ route('patient.diagnosis.test.index') }}">
                {{ __('messages.patient_diagnosis_test.diagnosis_test') }}
            </a>
        </li>
    @endmodule
@endrole --}}
@modulePermission('sms', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('sms*', 'mail*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('sms*') ? 'active' : '' }}" href="{{ route('sms.index') }}">
        {{ __('messages.sms.sms') }}
    </a>
</li>
@endmodulePermission

@modulePermission('mail', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('sms*', 'mail*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('mail*') ? 'active' : '' }}" href="{{ route('mail') }}">
        {{ __('messages.mail') }}
    </a>
</li>
@endmodulePermission

@modulePermission('incomes', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0  {{ !Request::is('incomes*', 'expenses*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('incomes*') ? 'active' : '' }}" href="{{ route('incomes.index') }}">
        {{ __('messages.incomes.incomes') }}
    </a>
</li>
@endmodulePermission
@modulePermission('expenses', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('incomes*', 'expenses*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('expenses*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
        {{ __('messages.expenses') }}
    </a>
</li>
@endmodulePermission
@modulePermission('item-categories', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('item-categories*', 'items*', 'item-stocks*', 'issued-items*', 'stores*', 'units*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('item-categories*') ? 'active' : '' }}"
       href="{{ route('item-categories.index') }}">
        {{ __('messages.items_categories') }}
    </a>
</li>
@endmodulePermission
@modulePermission('units', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('item-categories*', 'items*', 'item-stocks*', 'issued-items*', 'stores*', 'units*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('units*') ? 'active' : '' }}" href="{{ route('units.index') }}">
        {{ __('messages.unit.units') }}
    </a>
</li>
@endmodulePermission
@modulePermission('items', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('item-categories*', 'items*', 'item-stocks*', 'issued-items*', 'stores*', 'units*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('items*') ? 'active' : '' }}" href="{{ route('items.index') }}">
        {{ __('messages.items') }}
    </a>
</li>
@endmodulePermission
@modulePermission('stores', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('item-categories*', 'items*', 'item-stocks*', 'issued-items*', 'stores*', 'units*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('stores*') ? 'active' : '' }}"
       href="{{ route('stores.index') }}">
        {{ __('messages.store.stores') }}
    </a>
</li>
@endmodulePermission
@modulePermission('item-stocks', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('item-categories*', 'items*', 'item-stocks*', 'issued-items*', 'stores*', 'units*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('item-stocks*') ? 'active' : '' }}"
       href="{{ route('item.stock.index') }}">
        {{ __('messages.items_stocks') }}
    </a>
</li>
@endmodulePermission
@modulePermission('issued-items', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('item-categories*', 'items*', 'item-stocks*', 'issued-items*', 'stores*', 'units*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('issued-items*') ? 'active' : '' }}"
       href="{{ route('issued.item.index') }}">
        {{ __('messages.issued_items') }}
    </a>
</li>
@endmodulePermission
@modulePermission('charge-types', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('charge-types*', 'charge-categories*', 'charges*', 'doctor-opd-charges*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('charge-types*') ? 'active' : '' }}"
       href="{{ route('charge-types.index') }}">
        {{ __('messages.charge_type.charge_types') }}
    </a>
</li>
@endmodulePermission
@modulePermission('charge-categories', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('charge-types*', 'charge-categories*', 'charges*', 'doctor-opd-charges*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('charge-categories*') ? 'active' : '' }}"
       href="{{ route('charge-categories.index') }}">
        {{ __('messages.charge_categories') }}
    </a>
</li>
@endmodulePermission
@modulePermission('charges', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('charge-types*', 'charge-categories*', 'charges*', 'doctor-opd-charges*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('charges*') ? 'active' : '' }}" href="{{ route('charges.index') }}">
        {{ __('messages.charges') }}
    </a>
</li>
@endmodulePermission
@modulePermission('doctor-opd-charges', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('charge-types*', 'charge-categories*', 'charges*', 'doctor-opd-charges*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('doctor-opd-charges*') ? 'active' : '' }}"
       href="{{ route('doctor-opd-charges.index') }}">
        {{ __('messages.doctor_opd_charges') }}
    </a>
</li>
@endmodulePermission
@modulePermission('call-logs', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('call-logs*', 'visitor*', 'receives*', 'dispatches*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('call-logs*') ? 'active' : '' }}" href="{{ route('call_logs.index') }}">
        {{ __('messages.call_logs') }}
    </a>
</li>
@endmodulePermission
@modulePermission('visitors', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('call-logs*', 'visitor*', 'receives*', 'dispatches*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('visitors*') ? 'active' : '' }}" href="{{ route('visitors.index') }}">
        {{ __('messages.visitors') }}
    </a>
</li>
@endmodulePermission
@modulePermission('receives', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('call-logs*', 'visitor*', 'receives*', 'dispatches*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('receives*') ? 'active' : '' }}" href="{{ route('receives.index') }}">
        {{ __('messages.postal_receive') }}
    </a>
</li>
@endmodulePermission
@modulePermission('dispatches', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('call-logs*', 'visitor*', 'receives*', 'dispatches*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('dispatches*') ? 'active' : '' }}"
       href="{{ route('dispatches.index') }}">
        {{ __('messages.postal_dispatch') }}
    </a>
</li>
@endmodulePermission
@modulePermission('live-consultation', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('live-consultation*', 'live-meeting*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('live-consultation*') ? 'active' : '' }}"
       href="{{ route('live.consultation.index') }}">
        {{ __('messages.live_consultations') }}
    </a>
</li>
@endmodulePermission
@modulePermission('live-meeting', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0  {{ !Request::is('live-consultation*', 'live-meeting*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('live-meeting*') ? 'active' : '' }}"
       href="{{ route('live.meeting.index') }}">
        {{ __('messages.live_meetings') }}
    </a>
</li>
@endmodulePermission

@modulePermission('settings', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0  {{ Request::is('settings*', 'currency-settings*', 'hospital-schedules', 'operation-categories*', 'operations*', 'payment-gateways*','roles*','permissions*') ? '' : 'd-none' }}">
    <a class="nav-link p-0  {{ isset($sectionName) && $sectionName == 'general' ? 'active' : '' }}"
       href="{{ route('settings.edit', ['section' => 'general']) }}">
        {{ __('messages.general') }}
    </a>
</li>
@endmodulePermission
@modulePermission('hospital-schedules', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ Request::is('settings*', 'currency-settings*', 'hospital-schedules', 'operation-categories*', 'operations*', 'payment-gateways*','roles*','permissions*') ? '' : 'd-none' }}">
    <a class="nav-link p-0 {{ Request::is('hospital-schedules*') ? 'active' : '' }}"
       href="{{ route('hospital-schedules.index') }}">
        {{ __('messages.hospital_schedule') }}
    </a>
</li>
@endmodulePermission
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0  {{ Request::is('settings*', 'currency-settings*', 'hospital-schedules', 'operation-categories*', 'operations*', 'payment-gateways*','roles*','permissions*') ? '' : 'd-none' }} ">
    <a class="nav-link p-0 {{ isset($sectionName) && $sectionName == 'sidebar-setting' ? 'active' : '' }}"
       href="{{ route('settings.edit', ['section' => 'sidebar-setting']) }}">
        {{ __('messages.sidebar_setting') }}
    </a>
</li>
@modulePermission('currency-settings', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0  {{ Request::is('settings*', 'currency-settings*', 'hospital-schedules', 'operation-categories*', 'operations*', 'payment-gateways*','roles*','permissions*') ? '' : 'd-none' }} ">
    <a class="nav-link p-0 {{ Request::is('currency-settings*') ? 'active' : '' }}"
       href="{{ route('currency-settings.index') }}">
        {{ __('messages.currency_setting') }}
    </a>
</li>
@endmodulePermission

@modulePermission('front-settings', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('front-settings*', 'notice-boards*', 'testimonials*', 'front-cms-services*', 'terms-and-conditions*') ? 'd-none' : '' }}">
    <a class="nav-link p-0  {{ Request::is('front-settings*') ? 'active' : '' }}"
       href="{{ route('front.settings.index') }}">
        {{ __('messages.cms') }}
    </a>
</li>
@endmodulePermission
@modulePermission('front-cms-services', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('front-settings*', 'notice-boards*', 'testimonials*', 'front-cms-services*', 'terms-and-conditions*') ? 'd-none' : '' }}">
    <a class="nav-link p-0  {{ Request::is('front-cms-services*') ? 'active' : '' }}"
       href="{{ route('front.cms.services.index') }}">
        {{ __('messages.front_cms_services') }}
    </a>
</li>
@endmodulePermission
@modulePermission('notice-boards', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('front-settings*', 'notice-boards*', 'testimonials*', 'front-cms-services*', 'terms-and-conditions*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('notice-boards*') ? 'active' : '' }}" href="{{ url('notice-boards') }}">
        {{ __('messages.notice_boards') }}
    </a>
</li>
@endmodulePermission

@modulePermission('testimonials', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('front-settings*', 'notice-boards*', 'testimonials*', 'front-cms-services*', 'terms-and-conditions*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('testimonials*') ? 'active' : '' }}"
       href="{{ route('testimonials.index') }}">
        {{ __('messages.testimonials') }}
    </a>
</li>
@endmodulePermission
{{-- <li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ (!Request::is('enquiries*','enquiry*')) ? 'd-none' : '' }}" --}}
{{-- > --}}
@modulePermission('enquiries', 'view')
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ Request::is('enquiries*', 'enquiry*') ? '' : 'd-none' }}">
    <a class="nav-link p-0  {{ Request::is('enquiries*') || Request::is('enquiry*') ? 'active' : '' }}"
       href="{{ route('enquiries') }}">
        {{ __('messages.enquiries') }}
    </a>
</li>
@endmodulePermission
@role('Doctor')
@module('Doctors', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('employee/doctor*', 'schedules*', 'doctors*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('employee/doctor*') ? 'active' : '' }}"
       href="{{ url('employee/doctor') }}">
        {{ __('messages.doctors') }}
    </a>
</li>
@endmodule
@module('Schedules', $modules)
@if (getDoctorSchedule() != '' || getDoctorSchedule() != null || !empty(getDoctorSchedule()))
    <li
            class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('schedules*', 'holidays*', 'breaks*') ? 'd-none' : '' }}">
        <a class="nav-link p-0 {{ Request::is('schedules*') ? 'active' : '' }}"
           href="{{ route('schedules.edit', getDoctorSchedule()) }}">
            {{ __('messages.schedules') }}
        </a>
    </li>
@endif
@endmodule
@module('Prescriptions', $modules)
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('prescriptions*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('prescriptions*') ? 'active' : '' }}"
       href="{{ route('prescriptions.index') }}">
        {{ __('messages.prescriptions') }}
    </a>
</li>
@endmodule
@endrole
@role('Doctor|Accountant|Case Manager|Receptionist|Pharmacist|Lab Technician|Nurse|Patient')
@module('Notice Boards', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('employee/notice-board*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('employee/notice-board*') ? 'active' : '' }}"
       href="{{ url('employee/notice-board') }}">
        {{ __('messages.notice_boards') }}
    </a>
</li>
@endmodule
@endrole
@role('Doctor|Accountant|Case Manager|Receptionist|Pharmacist|Lab Technician|Nurse')
@module('My Payrolls', $modules)
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('employee/payroll*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('employee/payroll*') ? 'active' : '' }}" href="{{ route('payroll') }}">
        {{ __('messages.my_payrolls') }}
    </a>
</li>
@endmodule
@endrole
@role('Patient')
@module('Patient Cases', $modules)
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('patient/my-cases*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('patient/my-cases*') ? 'active' : '' }}"
       href="{{ url('patient/my-cases') }}">
        {{ __('messages.patients_cases') }}
    </a>
</li>
@endmodule
@endrole
@role('Patient')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('patient-smart-card*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('patient-smart-card*') ? 'active' : '' }}"
       href="{{ route('patient.smart.card.index') }}">
        {{ __('messages.patient_id_card.patient_id_card') }}
    </a>
</li>
@endrole
@role('Patient')
@module('Patient Admissions', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('employee/patient-admissions*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('employee/patient-admissions*') ? 'active' : '' }}"
       href="{{ url('employee/patient-admissions') }}">
        {{ __('messages.patient_admissions') }}
    </a>
</li>
@endmodule
@endrole
@role('Patient')
@module('Prescriptions', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('patient/my-prescriptions*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('patient/my-prescriptions*') ? 'active' : '' }}"
       href="{{ route('prescriptions.list') }}">
        {{ __('messages.prescriptions') }}
    </a>
</li>
@endmodule
@endrole
@role('Patient')
@module('Vaccinated Patients', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('patient/my-vaccinated*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('patient/my-vaccinated*') ? 'active' : '' }}"
       href="{{ route('patient.vaccinated') }}">
        {{ __('messages.vaccinated_patients') }}
    </a>
</li>
@endmodule
@endrole
@role('Patient')
@module('IPD Patients', $modules)
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('patient/my-ipds*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('patient/my-ipds*') ? 'active' : '' }}"
       href="{{ route('patient.ipd') }}">
        {{ __('messages.ipd_patients') }} test
    </a>
</li>
@endmodule
@module('OPD Patients', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('opds*', 'patient/my-opds*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('patient/my-opds*') ? 'active' : '' }}"
       href="{{ route('patient.opd') }}">
        {{ __('messages.opd_patients') }}
    </a>
</li>
@endmodule
@module('Maternity Patients', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('maternity*', 'patient/my-maternity*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('patient/my-maternity*') ? 'active' : '' }}"
       href="{{ route('patient.maternity') }}">
        {{ __('messages.maternity.maternity_patients') }}
    </a>
</li>
@endmodule
@endrole
@role('Patient')
@module('Diagnosis Tests', $modules)
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('birth-reports*', 'death-reports*', 'investigation-reports*', 'operation-reports*', 'employee/patient-diagnosis-test*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('employee/patient-diagnosis-test*') ? 'active' : '' }}"
       href="{{ url('employee/patient-diagnosis-test') }}">
        {{ __('messages.patient_diagnosis_test.diagnosis_test') }}
    </a>
</li>
@endmodule
@endrole
@role('Patient')
@module('Invoices', $modules)
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('employee/invoices*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('employee/invoices*') ? 'active' : '' }}"
       href="{{ url('employee/invoices') }}">
        {{ __('messages.invoices') }}
    </a>
</li>
@endmodule
@endrole
@role('Patient')
@module('Bills', $modules)
<li class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ !Request::is('employee/bills*') ? 'd-none' : '' }}">
    <a class="nav-link p-0 {{ Request::is('employee/bills*') ? 'active' : '' }}"
       href="{{ url('employee/bills') }}">
        {{ __('messages.bills') }}
    </a>
</li>
@endmodule
@endrole
@modulePermission('operation-categories', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ Request::is('settings*', 'currency-settings*', 'hospital-schedules', 'operation-categories*', 'operations*', 'payment-gateways*','roles*','permissions*') ? '' : 'd-none' }}">
    <a class="nav-link p-0 {{ Request::is('operation-categories*') ? 'active' : '' }}"
       href="{{ route('operation.category.index') }}">
        {{ __('messages.operation_category.operation_categories') }}
    </a>
</li>
@endmodulePermission

@modulePermission('operations', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ Request::is('settings*', 'currency-settings*', 'hospital-schedules', 'operation-categories*', 'operations*', 'payment-gateways*','roles*','permissions*') ? '' : 'd-none' }}">
    <a class="nav-link p-0 {{ Request::is('operations*') ? 'active' : '' }}"
       href="{{ route('operations.index') }}">
        {{ __('messages.operations') }}
    </a>
</li>
@endmodulePermission
@modulePermission('payment-gateways', 'view')
<li
        class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ Request::is('settings*', 'currency-settings*', 'hospital-schedules', 'operation-categories*', 'operations*', 'payment-gateways*','roles*','permissions*') ? '' : 'd-none' }}">
    <a class="nav-link p-0 {{ Request::is('payment-gateways*') ? 'active' : '' }}"
       href="{{ route('payment-gateways.index') }}">
        {{ __('messages.payment_gateways') }}
    </a>
</li>
@endmodulePermission
@modulePermission('roles', 'view')
<li  class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ Request::is('settings*', 'currency-settings*', 'hospital-schedules', 'operation-categories*', 'operations*', 'payment-gateways*','roles*','permissions*') ? '' : 'd-none' }}">
    <a class="nav-link p-0 {{ Request::is('roles*') ? 'active' : '' }}"
       href="{{ route('roles.index') }}">
        {{ __('messages.roles') }}
    </a>
</li>
@endmodulePermission
@modulePermission('permissions', 'view')
<li  class="nav-item position-relative mx-xl-3 mb-3 mb-xl-0 {{ Request::is('settings*', 'currency-settings*', 'hospital-schedules', 'operation-categories*', 'operations*', 'payment-gateways*','roles*','permissions*') ? '' : 'd-none' }}">
    <a class="nav-link p-0 {{ Request::is('permissions*') ? 'active' : '' }}"
       href="{{ route('permissions.index') }}">
        Permissions
    </a>
</li>
@endmodulePermission
