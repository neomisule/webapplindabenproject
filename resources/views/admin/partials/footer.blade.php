        <footer class="footer mt-auto py-3 bg-white text-center">
            <div class="container">
                <span class="text-muted"> Copyright © <span id="year"></span> <a
                        href="javascript:void(0);" class="text-dark fw-medium">LindaBen</a>.
                    Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="" target="_blank">
                        <span class="fw-medium text-primary">LindaBen</span>
                    </a> All
                    rights
                    reserved
                </span>
            </div>
        </footer>

        </div>

        <div class="scrollToTop">
            <span class="arrow"><i class="ti ti-arrow-narrow-up fs-20"></i></span>
        </div>

        <div id="responsive-overlay"></div>
        <script src="{{ asset('admin/libs/@popperjs/core/umd/popper.min.js') }}"></script>
        <script src="{{ asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('admin/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('admin/js/sticky.js') }}"></script>
        <script src="{{ asset('admin/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('admin/js/simplebar.js') }}"></script>
        <script src="{{ asset('admin/libs/@tarekraafat/autocomplete.js/autoComplete.min.js') }}"></script>
        <script src="{{ asset('admin/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

        <script src="{{ asset('admin/js/defaultmenu.min.js') }}"></script>
        <script src="{{ asset('admin/libs/flatpickr/flatpickr.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('admin/js/treeselect.js') }}"></script>

        @stack('js')
        @include('admin.partials.footer_script')
