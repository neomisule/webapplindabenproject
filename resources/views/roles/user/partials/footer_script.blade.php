<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<script>
    // Initialize CKEditor for text areas
    $('.ckeditor').each(function() {
        CKEDITOR.replace(this.id, {
            toolbar: [{
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat']
                },
                {
                    name: 'paragraph',
                    items: ['NumberedList', 'BulletedList', '-', 'Blockquote']
                },
                {
                    name: 'links',
                    items: ['Link', 'Unlink']
                },
                {
                    name: 'insert',
                    items: ['Image', 'Table']
                },
                {
                    name: 'tools',
                    items: ['Maximize']
                },
                {
                    name: 'styles',
                    items: ['Styles', 'Format', 'Font', 'FontSize']
                },
                {
                    name: 'colors',
                    items: ['TextColor', 'BGColor']
                }
            ],
            height: 200
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".sortable, .sortable-child, .sortable-grandchild").sortable({
            handle: ".drag-handle",
            connectWith: ".sortable, .sortable-child, .sortable-grandchild",
            placeholder: "ui-sortable-placeholder",
            tolerance: "pointer",
            over: function(event, ui) {

                if ($(this).hasClass('sortable-grandchild') && ui.item.closest('.sortable-child').length) {
                    $(this).addClass('sortable-highlight');
                }
            },
            out: function(event, ui) {
                $(this).removeClass('sortable-highlight');
            },
            receive: function(event, ui) {

                if ($(this).hasClass('sortable-grandchild')) {
                    ui.item.find('.toggle-menu, .delete-menu').data('type', 'grandchild');
                }
            },
            update: function(event, ui) {
                saveMenuOrder();
            },
            start: function(event, ui) {

                $('.potential-drop').css('min-height', '20px');
            },
            stop: function(event, ui) {

                $('.potential-drop').not(':has(li)').css('min-height', '10px');
            }
        }).disableSelection();

        function saveMenuOrder() {
            $('.loading-indicator').show();

            var menuData = [];
            $('#main-menu > li').each(function(parentIndex) {
                var parentId = $(this).data('id');
                menuData.push({
                    id: parentId,
                    parent_id: null,
                    order: parentIndex + 1
                });
                $(this).find('> .sortable-child > li').each(function(childIndex) {
                    var childId = $(this).data('id');
                    menuData.push({
                        id: childId,
                        parent_id: parentId,
                        order: childIndex + 1
                    });
                    $(this).find('> .sortable-grandchild > li').each(function(grandchildIndex) {
                        menuData.push({
                            id: $(this).data('id'),
                            parent_id: childId,
                            order: grandchildIndex + 1
                        });
                    });
                });
            });


        }


    });
</script>

<script>
    // Toastr initialization
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };

    // Display toast messages from Laravel
    @if(Session::has('toast'))
    toastr["{{ Session::get('toast.type') }}"]("{{ Session::get('toast.message') }}", "{{ Session::get('toast.title') }}");
    @endif

    // Handle AJAX toast messages
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (xhr.responseJSON && xhr.responseJSON.toast) {
            toastr[xhr.responseJSON.toast.type](xhr.responseJSON.toast.message, xhr.responseJSON.toast.title);
        }
    });

    // SweetAlert for delete confirmation
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // SweetAlert for status confirmation
    $(document).on('click', '.toggle-btn', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "update status this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>

<script>
    function showLoading(button) {
        const btn = $(button);
        const spinner = btn.find('.spinner-border');
        const btnText = btn.find('.btn-text');

        spinner.removeClass('d-none');
        btnText.addClass('d-none');

        btn.prop('disabled', true);

        setTimeout(() => {
            window.location.href = btn.attr('href');
        }, 100);
    }

    $(document).on('click', '.edit-btn', function(e) {
        showLoading(this);
        e.preventDefault();
    });
</script>
