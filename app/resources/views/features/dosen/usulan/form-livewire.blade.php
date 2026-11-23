<div>
    {{-- 1. PEMBUNGKUS UTAMA (ROOT) --}}
    {{-- PROGRESS TRACKER --}}
    <div class="card card-body border-0 shadow-sm mb-4">
        <div class="d-flex justify-content-around text-center">
            @foreach (['Judul', 'Personil', 'Luaran', 'RAB', 'Mitra', 'Unggah', 'Konfirmasi'] as $i => $step)
                @php $stepNum = $i + 1; @endphp
                <div class="{{ $currentStep == $stepNum ? 'text-primary fw-bold' : 'text-muted' }}"
                    @if (!$isReadOnly) style="cursor: pointer;" wire:click="nextStep({{ $stepNum }})" @endif>

                    <div class="rounded-circle mb-1 mx-auto {{ $currentStep == $stepNum ? 'bg-primary text-white' : 'bg-light' }}"
                        style="width:35px; height:35px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        {{ $stepNum }}
                    </div>
                    <small class="d-none d-md-block">{{ $step }}</small>
                </div>
            @endforeach
        </div>
    </div>

       @if ($currentStep == 1)
        @include('features.dosen.usulan.steps.step1')
    @elseif ($currentStep == 2)
        @include('features.dosen.usulan.steps.step2')
    @elseif ($currentStep == 3)
        @include('features.dosen.usulan.steps.step3')
    @elseif ($currentStep == 4)
        @include('features.dosen.usulan.steps.step4')
    @elseif ($currentStep == 5)
        @include('features.dosen.usulan.steps.step5')
    @elseif ($currentStep == 6)
        @include('features.dosen.usulan.steps.step6')
    @elseif ($currentStep == 7)
        @include('features.dosen.usulan.steps.step7')
    @endif

   @include('features.dosen.usulan.modals.modal-search-member')
@include('features.dosen.usulan.modals.modal-add-mitra')
    {{-- @livewire('dosen.member-invitation-livewire') --}}






