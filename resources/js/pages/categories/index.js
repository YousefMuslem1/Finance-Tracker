
//************* add new category ***************
$(document).ready(function () {
    const submitButton = $('#newCategoryModal-submit-button');
    const categoryNameInput = $('#categoryName');
    const csrfToken = $('#csrf_token').val();
    const checkNameRoute = $('#check_name_route').val();
    
    let typingTimer;
    const typingDelay = 500;

    // Check the field after the user stops typing
    categoryNameInput.on('input', function () {
        clearTimeout(typingTimer); // Clear the previous timeout if the user is typing
        let categoryName = $(this).val();

        if (categoryName.length > 0) {
            typingTimer = setTimeout(() => {
                // Send the request after the user stops typing
                $.ajax({
                    url: checkNameRoute,
                    method: 'POST',
                    data: {
                        _token: csrfToken, // تمرير CSRF Token
                        category_name: categoryName,
                    },
                    success: function (response) {
                        if (response.exists) {
                            categoryNameInput.addClass('is-invalid');
                            $('#nameFeedback').text('This category name is already taken.');
                            submitButton.prop('disabled', true);
                        } else {
                            categoryNameInput.removeClass('is-invalid').addClass('is-valid');
                            $('#nameFeedback').text('');
                            submitButton.prop('disabled', false);
                        }
                    },
                });
            }, typingDelay);
        } else {
            categoryNameInput.removeClass('is-invalid is-valid');
            $('#nameFeedback').text('');
            submitButton.prop('disabled', true);
        }
    });
});

//************* edit category ***************
$(document).ready(function () {
    const submitButton = $('#editCategoryModal-submit-button');
    const categoryNameInput = $('#editCategoryName');
    const csrfToken = $('#csrf_token_edit').val();
    const checkNameRoute = $('#check_name_route_edit').val();
    const updateRouteTemplate = $('#editCategoryUpdateRoute').data('url');
    let originalCategoryName = $('#editCategoryName').val();  // تخزين الاسم الأصلي
    let typingTimer;
    const typingDelay = 500; 

    // Check the field after the user stops typing
    categoryNameInput.on('input', function () {
        const categoryName = $(this).val();

        // تحقق من الاسم فقط إذا كان قد تغير عن الاسم الأصلي
        if (categoryName !== originalCategoryName) {
            clearTimeout(typingTimer); // Clear the previous timeout if the user is typing

            if (categoryName.length > 0) {
                typingTimer = setTimeout(() => {
                    // Send the request after the user stops typing
                    $.ajax({
                        url: checkNameRoute,
                        method: 'POST',
                        data: {
                            _token: csrfToken, // تمرير CSRF Token
                            category_name: categoryName,
                        },
                        success: function (response) {
                            if (response.exists) {
                                categoryNameInput.addClass('is-invalid');
                                $('#editNameFeedback').text('This category name is already taken.');
                                submitButton.prop('disabled', true);
                            } else {
                                categoryNameInput.removeClass('is-invalid').addClass('is-valid');
                                $('#editNameFeedback').text('');
                                submitButton.prop('disabled', false);
                            }
                        },
                    });
                }, typingDelay);
            } else {
                categoryNameInput.removeClass('is-invalid is-valid');
                $('#editNameFeedback').text('');
                submitButton.prop('disabled', true);
            }
        } else {
            // إذا تم الرجوع للاسم الأصلي، أوقف التحقق وامسح رسالة الخطأ
            categoryNameInput.removeClass('is-invalid is-valid');
            $('#editNameFeedback').text('');
            submitButton.prop('disabled', false);  // تمكين الزر عند العودة للاسم الأصلي
        }
    });

    // Populate the modal with category data when editing
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const type = $(this).data('type');
        const parentId = $(this).data('parent-id');

        // استبدال :id في الرابط الديناميكي
        const updateUrl = updateRouteTemplate.replace(':id', id);
        $('#editCategoryForm').attr('action', updateUrl);

        // Populate form fields with current category data
        $('#editCategoryName').val(name || '');
        $('#editCategoryType').val(type || '');
        $('#editParentCategory').val(parentId || '');
        $('#editCategoryId').val(id);

        // تخزين الاسم الأصلي للفئة
        originalCategoryName = name || '';

        // Show the modal
        $('#editCategoryModal').modal('show');
    });
});

//************* delete category ***************
$(document).ready(function () {
    $(document).on('click', '.btn-delete', function () {
        const categoryId = $(this).data('id');
        const categoryName = $(this).data('name');
        const deleteUrl = $(this).data('url');
        Swal.fire({
            title: `Are you sure you want to delete "${categoryName}"?`,
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'), // CSRF Token
                        _method: 'DELETE',
                    },
                    success: function (response) {
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        );
                        location.reload();
                    },
                    error: function () {
                        Swal.fire(
                            'Error!',
                            'Something went wrong. Please try again later.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});






