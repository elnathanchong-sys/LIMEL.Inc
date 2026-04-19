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

        const ok = confirm('Delete this product?');
        if (!ok) return;

        const url = $(this).data('post');
        const id = $(this).data('id');

        if (!id) return; // safety check

        const f = $('<form>', {
            method: 'POST',
            action: url
        });

        $('<input>').attr({
            type: 'hidden',
            name: 'productID',
            value: id
        }).appendTo(f);

        f.appendTo(document.body).submit();
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

    //-----------------------------Practical 7 JS------------------------------------------
    // Autofocus
    $('form :input:not(button):first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();
    
    // Confirmation message
    $('[data-confirm]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

    // Initiate GET request
    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        location = url || location;
    });

    // Initiate POST request
    $('[data-post]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.post;
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    // Reset form
    $('[type=reset]').on('click', e => {
        e.preventDefault();
        location = location;
    });

    // Auto uppercase
    $('[data-upper]').on('input', e => {
        const a = e.target.selectionStart;
        const b = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(a, b);
    });

    // Photo preview
    $('label.upload input[type=file]').on('change', e => {
        const f = e.target.files[0];
        const img = $(e.target).siblings('img')[0];

        if (!img) return;

        img.dataset.src ??= img.src;

        if (f?.type.startsWith('image/')) {
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = img.dataset.src;
            e.target.value = '';
        }
    });

    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const preview = document.getElementById('previewImg');

    // click to upload
    uploadArea.addEventListener('click', () => fileInput.click());

    // file change
    fileInput.addEventListener('change', e => {
        handleFile(e.target.files[0]);
    });

    // drag over
    uploadArea.addEventListener('dragover', e => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    // drag leave
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    // drop
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');

        const file = e.dataTransfer.files[0];
        fileInput.files = e.dataTransfer.files;
        handleFile(file);
    });

    // preview function
    function handleFile(file) {
        if (!file || !file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    const slides = document.querySelectorAll('input[name="r"]');
    let currentIndex = 0;

    function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        slides[currentIndex].checked = true;
    }

    let slideInterval = setInterval(nextSlide, 5000);

    slides.forEach((radio, index) => {
        radio.addEventListener('change', () => {
            currentIndex = index;
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        });
    });
});