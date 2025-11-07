<div class="stepper-nav d-flex justify-content-between flex-wrap mb-15">
    <!--begin::Step 1-->
    <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav" data-kt-stepper-action="step">
        <!--begin::Wrapper-->
        <div class="stepper-wrapper d-flex align-items-center">
            <!--begin::Icon-->
            <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">1</span>
            </div>
            <!--end::Icon-->

            <!--begin::Label-->
            <div class="stepper-label">
                <h3 class="stepper-title">
                    Step 1
                </h3>

                <div class="stepper-desc">
                    {{$label1}}
                </div>
            </div>
            <!--end::Label-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Line-->
        <div class="stepper-line h-40px"></div>
        <!--end::Line-->
    </div>
    <!--end::Step 1-->

    <!--begin::Step 2-->
    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
        <!--begin::Wrapper-->
        <div class="stepper-wrapper d-flex align-items-center">
            <!--begin::Icon-->
            <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">2</span>
            </div>
            <!--begin::Icon-->

            <!--begin::Label-->
            <div class="stepper-label">
                <h3 class="stepper-title">
                    Step 2
                </h3>

                <div class="stepper-desc">
                    {{$label2}}
                </div>
            </div>
            <!--end::Label-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Line-->
        <div class="stepper-line h-40px"></div>
        <!--end::Line-->
    </div>
    <!--end::Step 2-->

    <!--begin::Step 3-->
    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
        <!--begin::Wrapper-->
        <div class="stepper-wrapper d-flex align-items-center">
            <!--begin::Icon-->
            <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">3</span>
            </div>
            <!--begin::Icon-->

            <!--begin::Label-->
            <div class="stepper-label">
                <h3 class="stepper-title">
                    Step 3
                </h3>

                <div class="stepper-desc">
                    {{$label3}}
                </div>
            </div>
            <!--end::Label-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Line-->
        <div class="stepper-line h-40px"></div>
        <!--end::Line-->
    </div>
    <!--end::Step 3-->

    <!--begin::Step 4-->
    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
        <!--begin::Wrapper-->
        <div class="stepper-wrapper d-flex align-items-center">
            <!--begin::Icon-->
            <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">4</span>
            </div>
            <!--begin::Icon-->

            <!--begin::Label-->
            <div class="stepper-label">
                <h3 class="stepper-title">
                    Step 4
                </h3>

                <div class="stepper-desc">
                    {{$label4}}
                </div>
            </div>
            <!--end::Label-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Step 4-->
</div>

@push('scripts')
    <script>
        // Stepper lement
        var element = document.querySelector("#kt_stepper_example_clickable");

        // Initialize Stepper
        var stepper = new KTStepper(element);

        // Handle navigation click
        stepper.on("kt.stepper.click", function(stepper) {
            stepper.goTo(stepper.getClickedStepIndex()); // go to clicked step
        });

        // Handle next step
        stepper.on("kt.stepper.next", function(stepper) {
            stepper.goNext(); // go next step
        });

        // Handle previous step
        stepper.on("kt.stepper.previous", function(stepper) {
            stepper.goPrevious(); // go previous step
        });
    </script>
@endpush
