// 1
const {
    ClassicEditor,
    Essentials,
    Bold,
    Italic,
    Underline,
    Font,
    Paragraph,
    List,
    ListProperties,
    Alignment
} = CKEDITOR;

let editor;
ClassicEditor
    .create(document.querySelector('#editor'), {
        plugins: [Essentials, Bold, Italic, Underline, Font, Paragraph, List, ListProperties, Alignment],
        toolbar: [
            'undo', 'redo', '|', 'bold', 'italic', 'underline', '|',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
            'alignment', '|', 'indent', 'outdent', '|',
            'numberedList', 'bulletedList'
        ],
        list: {
            properties: {
                styles: true,
                startIndex: true,
                reversed: true
            }
        }
    })
    .then(newEditor => {
        editor = newEditor;
    })
    .catch(error => {
        console.error(error);
    });

// 2
const formulir = document.querySelector('.formulir');
formulir.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(formulir);
    formData.append('prodi', formulir.querySelector('[name="prodi"]').value);
    formData.append('kategori', formulir.querySelector('[name="kategori"]').value);
    formData.append('deskripsi', editor.getData());
    const fileInput = formulir.querySelector('[name="dokumentasi"]');
    if (fileInput.files.length > 0) {
        for (let i = 0; i < fileInput.files.length; i++) {
            formData.append('dokumentasi[]', fileInput.files[i]);
        }
    }
    Swal.fire({
        title: "Anda Yakin?",
        text: "Anda tidak dapat mengubah aspirasi setelah mengirimkannya.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Kirim!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Files being uploaded:');
            for (let file of fileInput.files) {
                console.log(file.name);
            }
            fetch(formulir.action, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Aspirasi Anda telah terkirim.",
                        icon: "success"
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat mengirim aspirasi.",
                        icon: "error"
                    });
                }
            })
            .catch(error => {
                console.error('Server Error:', error);
                Swal.fire({
                    title: "Error!",
                    text: `Terjadi kesalahan pada server: ${error.message}`,
                    icon: "error"
                });
            });
        }
    });
});

// 3
document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll(".animate-fade-in");
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("animate-show");
            }
        });
    }, {
        threshold: 0.2
    });
    elements.forEach(el => observer.observe(el));
});