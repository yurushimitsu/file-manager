function moveToArchive(itemName, isFolder) {
    Swal.fire({
        title: "Are you sure?",
        text: "Move this file to archive?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0D4AE5",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, move to archive"
    }).then((result) => {
        if (result.isConfirmed) {
            // Send the appropriate data to the backend
            fetch(window.Laravel.routes.moveToArchive, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken  // CSRF token for security
                },
                body: JSON.stringify({
                    item: itemName,
                    isFolder: isFolder  // Send whether it's a folder or not
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "Moved to Archive",
                        text: "Your item has been moved to archive",
                        // text: data.message,
                        icon: "success"
                    }).then(() => {
                        // location.reload();  // Reload the page to reflect changes
                        if (isFolder) {
                            document.getElementById('folder-' + itemName).remove();
                            document.getElementById('folderList-' + itemName).remove();
                        } else {
                            document.getElementById('file-' + itemName).remove();
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: "There was an issue moving the item to archive: " + data.message,
                        icon: "error"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: "Error!",
                    text: "There was an error with the request.",
                    icon: "error"
                });
            });
        }
    });
}

function moveToTrash(itemName, isFolder) {
    Swal.fire({
        title: "Are you sure?",
        text: "Move this file to trash?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0D4AE5",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, move to trash"
    }).then((result) => {
        if (result.isConfirmed) {
            // Send the appropriate data to the backend
            fetch(window.Laravel.routes.moveToTrash, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken  // CSRF token for security
                },
                body: JSON.stringify({
                    item: itemName,
                    isFolder: isFolder  // Send whether it's a folder or not
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "Moved to Trash",
                        text: "Your item has been moved to trash",
                        // text: data.message,
                        icon: "success"
                    }).then(() => {
                        // location.reload();  // Reload the page to reflect changes
                        if (isFolder) {
                            document.getElementById('folder-' + itemName).remove();
                            document.getElementById('folderList-' + itemName).remove();
                        } else {
                            document.getElementById('file-' + itemName).remove();
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: "There was an issue moving the item to trash: " + data.message,
                        icon: "error"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: "Error!",
                    text: "There was an error with the request.",
                    icon: "error"
                });
            });
        }
    });
}

function restoreFiles(itemName, isFolder) {
    Swal.fire({
        title: "Are you sure?",
        text: "Restore this file?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0D4AE5",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, restore"
    }).then((result) => {
        if (result.isConfirmed) {
            // Send the appropriate data to the backend
            fetch(window.Laravel.routes.restoreFiles, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken  // CSRF token for security
                },
                body: JSON.stringify({
                    item: itemName,
                    isFolder: isFolder  // Send whether it's a folder or not
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "File Restored",
                        text: "Your item has been restored",
                        // text: data.message,
                        icon: "success"
                    }).then(() => {
                        // location.reload();  // Reload the page to reflect changes
                        if (isFolder) {
                            document.getElementById('folder-' + itemName).remove();
                            document.getElementById('folderList-' + itemName).remove();
                        } else {
                            document.getElementById('file-' + itemName).remove();
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: "There was an issue moving the item to trash: " + data.message,
                        icon: "error"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: "Error!",
                    text: "There was an error with the request.",
                    icon: "error"
                });
            });
        }
    });
}

function deleteFile(itemName, isFolder, isEmpty) {
    Swal.fire({
        title: isEmpty ? "Empty Trash?" : "Delete File Permanently?",
        html: isEmpty ? `This will permanently delete <strong>all files and folders</strong> in the trash.<br>This action <span style="color:red;">cannot be undone</span>.` 
                        : `Are you sure you want to permanently delete this file?<br>This action <span style="color:red;">cannot be undone</span>.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0D4AE5",
        cancelButtonColor: "#d33",
        confirmButtonText: isEmpty ? "Yes, empty trash" : "Yes, delete permanently"
    }).then((result) => {
        if (result.isConfirmed) {
            // Send the appropriate data to the backend
            fetch(window.Laravel.routes.deleteFile, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken  // CSRF token for security
                },
                body: JSON.stringify({
                    item: itemName,
                    isFolder: isFolder,
                    isEmpty: isEmpty
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: isEmpty ? "Trash Emptied" : "File Deleted Permanently",
                        text: isEmpty ? "Trash has been emptied" : "Your item has been deleted",
                        icon: "success"
                    }).then(() => {
                        if (isEmpty) {
                            location.reload(); // Or clear trash container via JS
                        } else {
                            if (isFolder) {
                                document.getElementById('folder-' + itemName)?.remove();
                                document.getElementById('folderList-' + itemName)?.remove();
                            } else {
                                document.getElementById('file-' + itemName)?.remove();
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: "There was an issue deleting the file: " + data.message,
                        icon: "error"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: "Error!",
                    text: "There was an error with the request.",
                    icon: "error"
                });
            });
        }
    });
}
