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