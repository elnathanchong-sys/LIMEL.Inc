// ============================================================================
// General Functions (jQuery)
// ============================================================================
console.log("APP JS LOADED");
$(() => {

    // Autofocus first input
    $('form :input:not(button):first').focus();

    // Focus first error field
    $('.err:first').prev().find(':input:first').focus();

    // Confirm before actions (delete, etc.)
    $('[data-confirm]').on('click', function (e) {
        const text = $(this).data('confirm') || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
        }
    });

    $(document).on('click', '[data-get]', function (e) {
    e.preventDefault();

    const url = $(this).data('get');

    if (url) {
        window.location.href = url;
    }
    });

    // POST request navigation
    $('[data-post]').on('click', function (e) {
        e.preventDefault();

        const url = $(this).data('post') || window.location.href;

        const f = $('<form>', {
            method: 'POST',
            action: url
        }).appendTo(document.body);

        f[0].submit();
    });

    // Reset form (force reload)
    $('[type=reset]').on('click', function (e) {
        e.preventDefault();
        location.reload();
    });

    // Auto uppercase inputs
    $('[data-upper]').on('input', function (e) {
        const start = this.selectionStart;
        const end = this.selectionEnd;

        this.value = this.value.toUpperCase();

        this.setSelectionRange(start, end);
    });

    // Image preview for uploads
    $('label.insert-upload input[type=file]').on('change', function (e) {
        const file = this.files[0];
        const img = $(e.target).siblings('.insert-upload-box').find('img')[0];

        if (!img) return;

        img.dataset.src ??= img.src;

        if (file && file.type.startsWith('image/')) {
            img.src = URL.createObjectURL(file);
        } else {
            img.src = img.dataset.src;
            this.value = '';
        }
    });

    document.querySelector('input[type="file"]').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            alert("Selected file: " + file.name);
        }
    });
});