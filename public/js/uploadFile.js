const dropZone = document.getElementById('drop-zone');
const dragOverlay = document.getElementById('drag-overlay');
const fileUploadButton = document.getElementById('fileUploadButton');
const folderUploadButton = document.getElementById('folderUploadButton');
const closeUpload = document.getElementById('closeUpload');

let isUploading = false;  // Flag to prevent multiple SweetAlerts
let filesToUpload = 0;     // Counter for the number of files to upload
let filesUploaded = 0;     // Counter for the number of files successfully uploaded
let existingFiles = [];
let newFiles = [];

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dragOverlay.classList.remove('hidden');
});

dropZone.addEventListener('dragleave', (e) => {
    if (!dropZone.contains(e.relatedTarget)) {
        dragOverlay.classList.add('hidden');
    }
});

fileUploadButton.addEventListener('click', (e) => {
    e.preventDefault();
    dragOverlay.classList.remove('hidden');
});

folderUploadButton.addEventListener('click', (e) => {
    e.preventDefault();
    dragOverlay.classList.remove('hidden');
});

closeUpload.addEventListener('click', (e) => {
    e.preventDefault();
    dragOverlay.classList.add('hidden');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dragOverlay.classList.add('hidden');
    
    const items = e.dataTransfer.items;

    let folderNames = new Set(); // To track the folder names and avoid duplication

    // Count the number of files to upload
    filesToUpload = 0;
    for (let i = 0; i < items.length; i++) {
        const item = items[i].webkitGetAsEntry?.();
        if (item) {
            countFilesInItem(item);
        }
    }

    // Start the actual upload process after counting the files
    for (let i = 0; i < items.length; i++) {
        const item = items[i].webkitGetAsEntry?.();
        if (item) {
            traverseFileTree(item, folderNames);
        }
    }
});

function countFilesInItem(item, path = "") {
    if (item.isFile) {
        filesToUpload++;
    } else if (item.isDirectory) {
        const dirReader = item.createReader();
        dirReader.readEntries(entries => {
            for (const entry of entries) {
                countFilesInItem(entry, path + item.name + "/");
            }
        });
    }
}

function traverseFileTree(item, folderNames, path = "") {
    if (item.isFile) {
        item.file(file => {
            file.relativePath = path + file.name;
            uploadFile(file, file.relativePath, folderNames);
        });
    } else if (item.isDirectory) {
        if (folderNames.has(item.name)) {
            console.log(`Folder "${item.name}" already exists, skipping upload.`);
            return; // Skip folder if it already exists
        }

        folderNames.add(item.name);  // Track the folder name

        const dirReader = item.createReader();
        dirReader.readEntries(entries => {
            for (const entry of entries) {
                traverseFileTree(entry, folderNames, path + item.name + "/");
            }
        });
    }
}

function uploadFile(file, relativePath, folderNames) {
    Swal.fire({
        title: 'Checking...',
        text: 'Please wait while your files are being checked.',
        imageUrl: 'https://cdn.jsdelivr.net/npm/sweetalert2@11/src/swal2-spinner.gif',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(window.Laravel.routes.uplodaCheck, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.Laravel.csrfToken
        },
        body: JSON.stringify({ relativePath: relativePath })
    })
    .then(response => response.json())
    .then(data => {
        file.relativePath = relativePath;

        if (data.exists) {
            existingFiles.push(file);
        } else {
            newFiles.push(file);
        }

        if (existingFiles.length + newFiles.length === filesToUpload) {
            handleUploadDecision();
        }
    });
}

function handleUploadDecision() {
    if (existingFiles.length > 0) {
        const fileList = existingFiles.map(f => `• ${f.relativePath}`).join('<br>');

        Swal.fire({
            title: `${existingFiles.length} file(s) already exist`,
            html: `<div class="text-start font-inherit">${fileList}</div><br><span class="font-medium">Do you want to overwrite them?<span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#0D4AE5",
            cancelButtonColor: "#d33",
            confirmButtonText: 'Yes, overwrite',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                [...existingFiles, ...newFiles].forEach(file => {
                    proceedUpload(file, file.relativePath);
                });
            } else {
                // Reset values
                existingFiles = [];
                newFiles = [];
            }
        });
    } else {
        newFiles.forEach(file => {
            proceedUpload(file, file.relativePath);
        });
    }
}


function proceedUpload(file, relativePath) {
    if (!isUploading) {
        // Show the "Uploading..." SweetAlert with spinner
        isUploading = true; // Set the flag to prevent showing multiple SweetAlerts
        Swal.fire({
            title: 'Uploading...',
            text: 'Please wait while your files are being uploaded.',
            imageUrl: 'https://cdn.jsdelivr.net/npm/sweetalert2@11/src/swal2-spinner.gif',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    const formData = new FormData();
    formData.append('file', file);
    formData.append('relativePath', relativePath);
    formData.append('_token', window.Laravel.csrfToken);

    fetch(window.Laravel.routes.upload, {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            filesUploaded++;
            if (filesUploaded === filesToUpload) {
                // All files uploaded, show the "Upload Complete" SweetAlert
                // setTimeout(() => {
                    Swal.close(); // Close loading spinner
                    Swal.fire({
                        icon: 'success',
                        title: 'Upload Complete',
                        text: `${filesToUpload} file(s) uploaded successfully!`,
                        timer: 3000,
                        showConfirmButton: false,
                        willClose: () => { location.reload(); }
                    });

                    // setTimeout(() => location.reload(), 3000); // Reload after success message
                // }, 3000); // Show spinner for 3 seconds before success alert
            }
        } else {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Upload Failed',
                text: data.error || 'Something went wrong.',
            });
        }
    })
    .catch(error => {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Upload Failed',
            text: error.message || 'Something went wrong.',
        });
        console.error(error);
    });
}