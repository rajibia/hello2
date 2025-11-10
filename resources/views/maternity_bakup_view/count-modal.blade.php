<x-modal wire:model.defer="showCountModal" title="Patient Count by Date">
    <div class="row">
        <div class="mb-3">
            <label for="countDate" class="form-label">Select Date</label>
            <input type="date" class="form-control" wire:model.live="countDate" wire:change="getPatientCount">
        </div>
        <div class="mt-4">
            <p>Total patients for selected date:</p>
            <h2 class="text-center">{{ $patientCount }}</h2>
        </div>
    </div>
    <x-slot name="footer">
        <button wire:click="closeCountModal" class="btn btn-secondary">Close</button>
    </x-slot>
</x-modal>